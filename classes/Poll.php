<?php

class Poll
{

    private static function getPollsHTML($polls)
    {
        $pollsHTML = "";

        foreach ($polls as $poll)
        {
            !empty($poll['all_votes']) ? $poll['all_votes'] : 0;

            $votedStatus = "";

            if ($poll['voted'])
                $votedStatus = "Your vote has been submited! Click to see your answer";

            $pollsHTML .= "
                <a href='poll.php?poll=" . $poll['id'] . "' class='list-group-item'>
                    <h4 class='list-group-item-heading'>" . $poll['subject'] . "</h4>
                    <p class='list-group-item-text text-muted'>" . $poll['created'] . "</p>
                    <p class='list-group-item-text'><span class='badge bg-success'>" . $poll['all_votes'] . " votes</span></p>
                    <p class='list-group-item-text text-success'>" . $votedStatus . "</p>
                </a>
            ";
        }

        return $pollsHTML;
    }

    public static function getPolls()
    {
        if ($pollData = DB::query('SELECT * FROM polls WHERE status = 1 ORDER BY created ASC'))
        {

            for ($i = 0; $i < count($pollData); $i++)
            {
                // $options = DB::query('SELECT * FROM poll_options WHERE poll_id = :poll_id', array(':poll_id' => $pollData[$i]['id']));
                // $pollData[$i]['answers'] = $options;

                if (DB::query('SELECT SUM(vote_count) as total_votes FROM poll_votes WHERE poll_id = :poll_id', array(':poll_id' => $pollData[$i]['id']))[0]['total_votes'])
                {
                    $pollData[$i]['all_votes'] = DB::query('SELECT SUM(vote_count) as total_votes FROM poll_votes WHERE poll_id = :poll_id', array(':poll_id' => $pollData[$i]['id']))[0]['total_votes'];
                }
                else
                {
                    $pollData[$i]['all_votes'] = 0;
                }

                // Check if user already voted

                $pollData[$i]['voted'] = self::hasVoted($pollData[$i]['id']);

                /*
                if (DB::query('SELECT voted FROM user_voted WHERE poll_id = :poll_id AND user_id = :user_id', array(':poll_id' => $pollData[$i]['id'], ':user_id' => $_SESSION['user_id'])))
                {
                    $pollData[$i]['voted'] = true;
                }
                else
                {
                    $pollData[$i]['voted'] = false;
                }
                */
                // $pollData[$i]['all_votes'] = DB::query('SELECT SUM(vote_count) as total_votes FROM poll_votes WHERE poll_id = :poll_id', array(':poll_id' => $pollData[$i]['id']))[0]['total_votes'];
                // array_push($pollData[$i], $voteCount);
            }

        }

        $pollsHTML = self::getPollsHTML($pollData);

        return $pollsHTML;
    }

    public static function getPoll($pollId)
    {
        if (DB::query('SELECT * FROM polls WHERE id = :id AND status = 1', array(':id' => $pollId)))
        {
            $pollData = array();

            $pollData['poll'] = DB::query('SELECT * FROM polls WHERE id = :id AND status = 1', array(':id' => $pollId))[0];
            $pollData['options'] = DB::query('SELECT * FROM poll_options WHERE poll_id = :poll_id AND status = 1', array(':poll_id' => $pollId));

            return $pollData;
        }

        return false;
    }

    public static function getPollView()
    {

    }

    public static function hasVoted($pollId)
    {
        if (DB::query('SELECT voted FROM user_voted WHERE poll_id = :poll_id AND user_id = :user_id', array(':poll_id' => $pollId, ':user_id' => $_SESSION['user_id'])))
        {
            return true;
        }

        return false;
    }

    public static function vote($data = array())
    {
        if (!isset($data['poll_id']) || !isset($data['poll_option_id']))
        {
            return false;
        }
        else
        {
            // Check if user has already voted on that poll
            if (self::hasVoted($data['poll_id'])) {
                return false;
            }

            // Check if poll with that id exists in database
            if (!DB::query('SELECT id FROM polls WHERE id = :poll_id', array(':poll_id' => $data['poll_id']))) {
                return false;
            }

            // check if answer id of that poll is correct
            if (!DB::query('SELECT id FROM poll_options WHERE id = :id AND poll_id = :poll_id', array(':id' => $data['poll_option_id'], ':poll_id' => $data['poll_id']))) {
                return false;
            }

            if (!DB::query('SELECT * FROM poll_votes WHERE poll_id = :poll_id and poll_option_id = :poll_option_id', array(':poll_id' => $data['poll_id'], ':poll_option_id' => $data['poll_option_id'])))
            {
                DB::query('INSERT INTO poll_votes (poll_id, poll_option_id, vote_count) VALUES (:poll_id, :poll_option_id, 1)',array(':poll_id' => $data['poll_id'], ':poll_option_id' => $data['poll_option_id']));
            }
            else
            {
                DB::query('UPDATE poll_votes SET vote_count = vote_count + 1 WHERE poll_id = :poll_id AND poll_option_id = :poll_option_id', array(':poll_id' => $data['poll_id'], ':poll_option_id' => $data['poll_option_id']));
            }

            DB::query('INSERT INTO user_voted VALUES (\'\', :poll_id, :poll_option_id, :user_id, 1)', array(':poll_id' => $data['poll_id'], ':poll_option_id' => $data['poll_option_id'], ':user_id' => $_SESSION['user_id']));

            return true;
        }

    }

    public static function getResults($pollId)
    {

        if (!DB::query('SELECT id FROM polls WHERE id = :id', array(':id' => $pollId)))
        {
            return false;
        }

        $pollData = array();

        $results = DB::query('SELECT p.subject, SUM(v.vote_count) AS total_votes
                              FROM poll_votes AS v 
                              LEFT JOIN polls AS p ON p.id = v.poll_id
                              WHERE poll_id = :poll_id', array(':poll_id' => $pollId))[0];

        if (!empty($results))
        {
            $pollData['poll'] = $results['subject'];
            $pollData['total_votes'] = $results['total_votes'];

            $optionResults = DB::query('SELECT o.id, o.name, v.vote_count 
                                        FROM poll_options AS o
                                        LEFT JOIN poll_votes AS v ON v.poll_option_id = o.id
                                        WHERE o.poll_id = :poll_id', array(':poll_id' => $pollId));

            if (!empty($optionResults))
            {
                foreach ($optionResults as $res)
                {
                    $pollData['options'][$res['name']] = $res['vote_count'];
                }
            }

        }

        return !empty($pollData) ? $pollData : false;
    }

}


