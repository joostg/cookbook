<?php

namespace model\database;

use Illuminate\Database\Eloquent\Model;

class Ingredientrow extends Model
{
    protected $table = 'ingredientrows';

    public function recipe()
    {
        return $this->belongsTo('model\database\Recipe');
    }

    public function ingredient()
    {
        return $this->belongsTo('model\database\Ingredient');
    }

    public function quantity()
    {
        return $this->belongsTo('model\database\Quantity');
    }

    public function toString()
    {
        $ingredientArray = array();

        $multipleItems = false;
        $quantityPlural = false;

        if ($this->amount !== NULL) {
            $ingredientArray[] = round($this->amount,2);

            if ($this->amount > 1) {
                $multipleItems = true;
            }
        }

        if ($this->quantity !== NULL) {
            if (!$multipleItems) {
                $ingredientArray[] = $this->quantity->name;
            } else {
                if ($this->quantity->plural !== NULL) {
                    $quantityPlural = true;
                    $ingredientArray[] = $this->quantity->plural;
                } else {
                    $ingredientArray[] = $this->quantity->name;
                }
            }
        }

        if ($this->ingredient !== NULL) {
            if (!$multipleItems) {
                $ingredientArray[] = $this->ingredient->name;
            } else {
                if ($this->ingredient->plural !== NULL && !$quantityPlural && $this->quantity === NULL) {
                    $ingredientArray[] = $this->ingredient->plural;
                } else {
                    $ingredientArray[] = $this->ingredient->name;
                }
            }
        }

        return implode(' ', $ingredientArray);
    }
}