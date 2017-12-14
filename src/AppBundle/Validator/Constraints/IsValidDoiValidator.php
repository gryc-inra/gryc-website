<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Service\ReferenceManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidDoiValidator extends ConstraintValidator
{
    private $referenceManager;

    public function __construct(ReferenceManager $referenceManager)
    {
        $this->referenceManager = $referenceManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$this->referenceManager->isValidDoi($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
