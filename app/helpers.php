<?php

if (!function_exists('get_topic_status')) {
    function get_topic_status($end_date)
    {
        $today = new DateTime();
        $end_date = new DateTime($end_date);
        $voting_end_date = (clone $end_date)->modify('+10 days');

        if ($today < $end_date) {
            return [
                'status' => 'waiting',
                'message' => 'Voting will start on ' . $end_date->format('Y-m-d')
            ];
        } elseif ($today >= $end_date && $today <= $voting_end_date) {
            return [
                'status' => 'open',
                'message' => 'Voting is open until ' . $voting_end_date->format('Y-m-d')
            ];
        } else {
            return [
                'status' => 'passed',
                'message' => 'Voting has ended'
            ];
        }
    }
}
