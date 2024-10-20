<?php
/**
 * Created by PhpStorm.
 * User: maxch
 * Date: 21.06.17
 * Time: 10:59
 */

namespace app\modules\api;


use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @var string The prefix for api module URL.
     */
    public $urlPrefix = 'api';

    public $urlRules = [
        'PUT,PATCH <_c:[\w\-]+><id:\d+>' => 'api/<_c>/update',
        'DELETE <_c:[\w\-]+><id:\d+>' => 'api/<_c>/delete',
        'GET,HEAD <_c:[\w\-]+><id:\d+>' => 'api/<_c>/view',
        'POST <_c:[\w\-]+>' => 'api/<_c>/create',
        'GET,HEAD <_c:[\w\-]+>' => 'api/<_c>/index',
        '<_c:[\w\-]+><id:\d+>' => 'api/<_c>/options',
        '<_c:[\w\-]+>' => 'api/<_c>/options',
    ];

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        /** @var Module $module */

        $app->urlManager->addRules($this->getUrlRules(), false);
    }

    /**
     * @return array
     */
    protected function getUrlRules()
    {
        $urlRules = [];

        foreach ($this->urlRules as $patten => $route) {
            $urlRules[$this->sanitizeRule($this->addRulePrefix($patten, $this->urlPrefix))] = $route;
        }

        return $urlRules;
    }

    /**
     * @param string $pattern
     * @param string $prefix
     * @return mixed
     */
    protected function addRulePrefix($pattern, $prefix)
    {
        return substr_replace($pattern, $prefix . '/', (int)strpos($pattern, '<'), 0);
    }

    /**
     * @param string $patten
     * @return mixed
     */
    protected function sanitizeRule($patten)
    {
        return preg_replace('/\/+/', '/', trim($patten, '/'));
    }
}