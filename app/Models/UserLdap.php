<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use LdapRecord\Models\Model;



class UserLdap extends Model
{
    public static $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];


    public function scopeSearchByAttribut($query, $attribut_name, $value, $use_contains = false)
    {
        if (isset($value)) {
            if ($use_contains) {
                $query->where($attribut_name, 'contains', $value);
            } else {
                $query->where($attribut_name,  $value);
            }
        }
        return $query;
    }

    public function scopeSearch($query, $request, $dc)
    {
        $query->in(Crypt::decryptString($dc))
            ->where('objectClass', 'user')
            // ->whereNotContains('sAMAccountName', '@')
            ->SearchByAttribut('givenName',  $request->firstName, true)
            ->SearchByAttribut('sn', $request->lastName, true)
            ->SearchByAttribut('co', $request->country, true)
            ->SearchByAttribut('mail',  $request->email, true)
            ->SearchByAttribut('sAMAccountName', $request->username, true)
            ->SearchByAttribut('l',  $request->location, true)
            ->SearchByAttribut('department', $request->department, true)
            ->get();
    }
    public function getGroups()
    {
        $groups = [];
        if (isset($this->l[0])) {
            array_push($groups, $this->l[0]);
        }
        if (isset($this->department[0])) {
            array_push($groups, $this->department[0]);
        }
        if (isset($this->co[0])) {
            array_push($groups, $this->co[0]);
        }
        return  $groups;
    }
}
