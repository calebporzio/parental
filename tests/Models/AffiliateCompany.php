<?php

namespace Parental\Tests\Models;

use Parental\HasParent;

class AffiliateCompany extends Company
{
    use HasParent;

    /**
     * @return bool
     */
    public function SomethingThatOnlyAffiliateCanDo()
    {
        return true;
    }

    /**
     * Override this for custom design
     * @return array array of field_name => field_value
     */
    protected function getParentalAttributes()
    {
        return [
            'is_affiliate' => true
        ];
    }

}
