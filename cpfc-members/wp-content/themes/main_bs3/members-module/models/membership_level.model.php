<?php
namespace CPFCMembers;

// I know, overkill, but I couldn't think of a better place to put the info - Thomas
class MembershipLevelModel extends \CPFCMembers\Model
{
    public static function fetchAll()
    {
        return array(
            'platinum' => 'Platinum',
            'gold'     => 'Gold',
            'silver'   => 'Silver',
            'free'     => 'Free'
        );
    }
}