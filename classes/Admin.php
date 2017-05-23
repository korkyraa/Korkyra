<?php

class Admin
{

    public static function logIn($email, $pass)
    {
        if (DB::query('SELECT email FROM admins WHERE email = :email', array(':email' => $email)))
        {
            if (password_verify($pass, DB::query('SELECT password FROM admins WHERE email = :email', array(':email' => $email))[0]['password']))
            {
                $admin = DB::query('SELECT id, username FROM admins WHERE email = :email', array(':email' => $email));

                echo "<pre>";
                print_r($admin);
                echo "</pre>";

                $_SESSION['admin_id'] = $admin[0]['id'];
                $_SESSION['admin_username'] = $admin[0]['username'];

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public static function isLoggedIn()
    {
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']))
        {
            return $_SESSION['admin_id'];
        }

        return false;
    }

    public static function getPanel()
    {
        /*
        $content2 =  DB::query('SELECT p.id, p.subject, p.status, SUM(v.vote_count) AS total_votes
                               FROM polls AS p 
                               LEFT JOIN poll_votes AS v ON p.id = v.poll_id');
        */
        $content = DB::query('SELECT id, subject, status FROM polls ORDER BY created ASC');

        for ($i = 0; $i < count($content); $i++)
        {
            $content[$i]['total_votes'] = DB::query('SELECT SUM(vote_count) AS total_votes FROM poll_votes WHERE poll_id = :poll_id', array(':poll_id' => $content[$i]['id']))[0]['total_votes'];
        }

        return $content;
    }

    public static function changePollStatus($pollId)
    {
        if (!DB::query('SELECT id FROM polls WHERE id = :id', array(':id' => $pollId)))
        {
            return false;
        }

        if (DB::query('SELECT status FROM polls WHERE id = :id', array(':id' => $pollId))[0]['status'])
        {
            DB::query('UPDATE polls SET status = 0 WHERE id = :id', array(':id' => $pollId));
        }
        else
        {
            DB::query('UPDATE polls SET status = 1 WHERE id = :id', array(':id' => $pollId));
        }

        return true;
    }

    public static function deletePoll($pollId)
    {
        if (!DB::query('SELECT id FROM polls WHERE id = :id', array(':id' => $pollId)))
        {
            return false;
        }

        DB::query('DELETE FROM polls WHERE id = :id', array(':id' => $pollId));

        return true;
    }

    public static function createQuestion($question, $answers)
    {
        DB::query('INSERT INTO polls VALUES (\'\', :question, NOW(), NOW(), 1)', array(':question' => $question));

        $questionId = DB::query('SELECT id FROM polls ORDER BY id DESC LIMIT 1')[0]['id'];

        foreach ($answers as $answer)
        {
            DB::query('INSERT INTO poll_options VALUES (\'\', :question_id, :answer, NOW(), NOW(), 1)', array(':question_id' => $questionId, ':answer' => $answer));
        }
    }

}

