<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SchoolNameMatchesEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $emailName = strtolower(explode('@', $this->email)[0]);

        try {
            // Split "Lastname, Firstname MI" into parts
            [$lastname, $firstname_mi] = array_map('trim', explode(',', $value));
            [$firstname, $mi] = array_map('trim', explode(' ', $firstname_mi));
        } catch (\Exception $e) {
            // Invalid schoolname format
            $fail('The schoolname must be in the format "Lastname, Firstname MI".');
            return;
        }

        $lastname = strtolower($lastname);
        $firstname = strtolower($firstname);

        if (!str_contains($emailName, $lastname) && !str_contains($emailName, $firstname)) {
            $fail('The schoolname does not match the email.');
        }
    }
}
