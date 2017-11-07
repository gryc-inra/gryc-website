<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidDoi extends Constraint
{
    public $message = 'The DOI "{{ string }}" is not valid.';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
