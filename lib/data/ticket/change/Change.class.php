<?php
namespace kt\data\ticket\change;

use kt\data\DatabaseObject;
use kt\data\user\User;

class Change extends DatabaseObject {
    public $tableName = "ticket_change";

    public function getUser() {
        return new User($this->userID);
    }

    public function getFormattedChangedText() {
        $from = str_split($this->oldContent);
        $to = str_split($this->newContent);
        $diffValues = array();
        $diffMask = array();
        $dm = array();
        $n1 = count($from);
        $n2 = count($to);
        for ($j = -1; $j < $n2; $j++) $dm[-1][$j] = 0;
        for ($i = -1; $i < $n1; $i++) $dm[$i][-1] = 0;
        for ($i = 0; $i < $n1; $i++) { 
            for ($j = 0; $j < $n2; $j++) {
                if ($from[$i] == $to[$j]) {
                    $ad = $dm[$i - 1][$j - 1];
                    $dm[$i][$j] = $ad + 1;
                } else {
                    $a1 = $dm[$i - 1][$j];
                    $a2 = $dm[$i][$j - 1];
                    $dm[$i][$j] = max($a1, $a2);
                }
            }
        }

        $i = $n1 - 1;
        $j = $n2 - 1;
        while (($i > -1) || ($j > -1)) {
            if ($j > -1) {
                if ($dm[$i][$j - 1] == $dm[$i][$j]) {
                    $diffValues[] = $to[$j];
                    $diffMask[] = 1;
                    $j--;  
                    continue;              
                }
            }
            if ($i > -1) {
                if ($dm[$i - 1][$j] == $dm[$i][$j]) {
                    $diffValues[] = $from[$i];
                    $diffMask[] = -1;
                    $i--;
                    continue;              
                }
            }
            {
                $diffValues[] = $from[$i];
                $diffMask[] = 0;
                $i--;
                $j--;
            }
        }    
        $diffValues = array_reverse($diffValues);
        $diffMask = array_reverse($diffMask);
        $diff = array('values' => $diffValues, 'mask' => $diffMask);
        $diffval = $diff['values'];
        $diffmask = $diff['mask'];
        $n = count($diffval);
        $pmc = 0;
        $result = '';
        for ($i = 0; $i < $n; $i++) {
            $mc = $diffmask[$i];
            if ($mc != $pmc) {
                switch ($pmc) {
                    case -1: $result .= '</del>'; break;
                    case 1: $result .= '</ins>'; break;
                }
                switch ($mc) {
                    case -1: $result .= '<del>'; break;
                    case 1: $result .= '<ins>'; break;
                }
            }
            $result .= $diffval[$i];

            $pmc = $mc;
        }
        switch ($pmc) {
            case -1: $result .= '</del>'; break;
            case 1: $result .= '</ins>'; break;
        }
        return $result;
    }
}
