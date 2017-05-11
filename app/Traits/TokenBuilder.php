<?php 

/**
 * This trait depends of the Base trait,
 * because it's using the container instace 
 * and the getter
 */

namespace Sage\Traits;

trait TokenBuilder 
{
    /**
     * Generate a Jason Web Token according with
     * the settings of the app config
     *
     * @return string
     */
    public function generateToken()
    {
        // get the configurations
        $c = $this->configs->get('jwt');

        // generate JWT
        $token = $this->jwt->setIssuer($c['iss']) 
                           ->setAudience($c['aud'])
                           ->setSubject($c['sub'])
                           ->setId($c['jti'], true) 
                           ->setIssuedAt($c['iss'])
                           ->setNotBefore($c['nbf'])
                           ->setExpiration($c['exp'])
                           ->set('company', 'WebMax')
                           ->sign($this->signer, $c['key'])
                           ->getToken();

        return $token;
    }
}
