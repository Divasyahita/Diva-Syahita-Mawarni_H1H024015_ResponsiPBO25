<?php

class Pokemon {
    public $name;
    public $type;
    public $level;
    public $hp;
    public $specialMoves; 

    public function __construct($name, $type, $level=5, $hp=45, $specialMoves=[]) {
        $this->name = $name;
        $this->type = $type;
        $this->level = (int)$level;
        $this->hp = (int)$hp;
        $this->specialMoves = $specialMoves;
    }

    public function specialMove() {
        
        if ($this->name === 'Bulbasaur') {
            return [
                'name' => $this->specialMoves[0] ?? 'Vine Whip',
                'desc' => 'Serangan tumbuhan jarak dekat yang dapat menahan lawan sekaligus memanfaatkan energi alami untuk memulihkan sedikit HP.'
            ];
        }
        
        return [
            'name' => $this->specialMoves[0] ?? 'Special Move',
            'desc' => 'A special technique.'
        ];
    }

    public function train($type, $intensity) {
        $type = ucfirst(strtolower($type));
        $intensity = max(1, (int)$intensity);

        $oldLevel = $this->level;
        $oldHP = $this->hp;

        $baseLevelGain = floor($intensity / 20);
        $baseHPGain = floor($intensity / 5);

        $attackBonus = 0;
        $defenseBonus = 0;
        $speedBonus = 0;

        if ($type === 'Attack') {
            $attackBonus = 1;
        } elseif ($type === 'Defense') {
            $defenseBonus = 1;
        } elseif ($type === 'Speed') {
            $speedBonus = 1;
        }

        
        $typeEffectiveness = $this->getTypeEffectivenessForTraining($type);

        $levelGain = max(0, $baseLevelGain + round($typeEffectiveness['levelMultiplier'] * ($attackBonus + $defenseBonus + $speedBonus)));
        $hpGain = max(0, $baseHPGain + round($typeEffectiveness['hpMultiplier'] * ($attackBonus + $defenseBonus + $speedBonus)));

        $levelGain += ($intensity % 3 === 0) ? 1 : 0;
        $hpGain += ($intensity % 4 === 0) ? 2 : 0;

        $this->level += $levelGain;
        $this->hp += $hpGain;

        if ($this->hp > 9999) $this->hp = 9999;

        $note = "Latihan $type intensitas $intensity. (Tipe='{$this->type}', pengaruh tipe: " . $typeEffectiveness['note'] . ")";

        return [
            'type' => $type,
            'intensity' => $intensity,
            'oldLevel' => $oldLevel,
            'newLevel' => $this->level,
            'oldHP' => $oldHP,
            'newHP' => $this->hp,
            'levelGain' => $levelGain,
            'hpGain' => $hpGain,
            'note' => $note,
            'specialMove' => $this->specialMove()
        ];
    }

    private function getTypeEffectivenessForTraining($trainingType) {
        $trainingType = ucfirst(strtolower($trainingType));
        // default
        $levelMultiplier = 0;
        $hpMultiplier = 0;
        $note = '';

        switch (strtolower($this->type)) {
            case 'grass':
                if ($trainingType === 'Defense') {
                    $levelMultiplier = 1;
                    $hpMultiplier = 3;
                    $note = 'Grass >> Defense/HP recovery';
                } elseif ($trainingType === 'Attack') {
                    $levelMultiplier = 0;
                    $hpMultiplier = 1;
                    $note = 'Grass >> Attack sedikit';
                } elseif ($trainingType === 'Speed') {
                    $levelMultiplier = 0;
                    $hpMultiplier = 0;
                    $note = 'Grass >> Speed rendah';
                }
                break;

            case 'fire':
                if ($trainingType === 'Attack') {
                    $levelMultiplier = 2;
                    $hpMultiplier = 1;
                    $note = 'Fire >> Attack & Power';
                } elseif ($trainingType === 'Speed') {
                    $levelMultiplier = 1;
                    $hpMultiplier = 0;
                    $note = 'Fire >> Speed';
                } else {
                    $levelMultiplier = 0;
                    $hpMultiplier = 0;
                    $note = 'Fire >> Defense kurang';
                }
                break;

            case 'water':
                if ($trainingType === 'Defense') {
                    $levelMultiplier = 1;
                    $hpMultiplier = 2;
                    $note = 'Water >> Defense & HP';
                } elseif ($trainingType === 'Speed') {
                    $levelMultiplier = 1;
                    $hpMultiplier = 0;
                    $note = 'Water >> Speed agak';
                } else {
                    $levelMultiplier = 1;
                    $hpMultiplier = 1;
                    $note = 'Water >> Seimbang';
                }
                break;

            case 'electric':
                if ($trainingType === 'Speed') {
                    $levelMultiplier = 2;
                    $hpMultiplier = 0;
                    $note = 'Electric >> Speed & Agility';
                } elseif ($trainingType === 'Attack') {
                    $levelMultiplier = 1;
                    $hpMultiplier = 0;
                    $note = 'Electric >> Attack bagus';
                } else {
                    $levelMultiplier = 0;
                    $hpMultiplier = 0;
                    $note = 'Electric >> Defense kurang';
                }
                break;

            default:
                $levelMultiplier = 0;
                $hpMultiplier = 0;
                $note = 'Netral';
        }

        return [
            'levelMultiplier' => $levelMultiplier,
            'hpMultiplier' => $hpMultiplier,
            'note' => $note
        ];
    }
}
