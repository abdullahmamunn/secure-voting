<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $dates = ['start_date', 'end_date'];


    protected $fillable = ['name', 'start_date', 'end_date', 'status'];

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function candidates()
    {
        return $this->hasManyThrough(Candidate::class, Position::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getStatusAttribute()
    {
        $currentDate = now();

        if ($this->start_date > $currentDate) {
            return 'Upcoming Election';
        } elseif ($this->end_date < $currentDate) {
            return 'Election is Finished';
        } else {
            return 'Election is Running';
        }
    }
}
