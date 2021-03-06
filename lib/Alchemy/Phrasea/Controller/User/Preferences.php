<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2013 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Controller\User;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class Preferences implements ControllerProviderInterface
{

    /**
     * {@inheritDoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->before(function(Request $request) use ($app) {
            $app['firewall']->requireAuthentication();
        });

        /**
         * Save preferences
         *
         * name         : save_pref
         *
         * description  : Save User preferences
         *
         * method       : POST
         *
         * parameters   : none
         *
         * return       : JSON Response
         */
        $controllers->post('/', $this->call('saveUserPref'))
            ->bind('save_pref');

        /**
         * Save temporary preferences
         *
         * name         : save_temp_pref
         *
         * description  : Save temporary preferences
         *
         * method       : POST
         *
         * parameters   : none
         *
         * return       : JSON Response
         */
        $controllers->post('/temporary/', $this->call('saveTemporaryPref'))
            ->bind('save_temp_pref');

        return $controllers;
    }

    /**
     *  Save temporary user preferences
     *
     * @param  Application  $app
     * @param  Request      $request
     * @return JsonResponse
     */
    public function saveTemporaryPref(Application $app, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            $app->abort(400);
        }

        $prop = $request->request->get('prop');
        $value = $request->request->get('value');
        $success = false;
        $msg = _('Error while saving preference');

        if ($prop && $value) {
            $app['session']->set('phraseanet.' . $prop, $value);
            $success = true;
            $msg = _('Preference saved !');
        }

        return new JsonResponse(array('success' => $success, 'message' => $msg));
    }

    /**
     *  Save user preferenes
     *
     * @param  Application  $app
     * @param  Request      $request
     * @return JsonResponse
     */
    public function saveUserPref(Application $app, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            $app->abort(400);
        }

        $msg = _('Error while saving preference');
        $prop = $request->request->get('prop');
        $value = $request->request->get('value');

        $success = false;
        if (null !== $prop && null !== $value) {
            $app['authentication']->getUser()->setPrefs($prop, $value);
            $success = true;
            $msg = _('Preference saved !');
        }

        return new JsonResponse(array('success' => $success, 'message' => $msg));
    }

    /**
     * Prefix the method to call with the controller class name
     *
     * @param  string $method The method to call
     * @return string
     */
    private function call($method)
    {
        return sprintf('%s::%s', __CLASS__, $method);
    }
}
