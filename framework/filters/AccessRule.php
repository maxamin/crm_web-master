<?php

namespace app\framework\filters;

use yii\filters\AccessRule as BaseAccessRule;


class AccessRule extends BaseAccessRule
{

    public function allows($action, $user, $request)
    {
        $isMatchAction = $this->matchAction($action);
        if ($isMatchAction
            && $this->matchRole($user)
            && $this->matchIP($request->getUserIP())
            && $this->matchVerb($request->getMethod())
            && $this->matchController($action->controller)
            && $this->matchCustom($action)
        ) {
            return $this->allow ? true : false;
        } else {
            return $this->allow && $isMatchAction ? false : null;
        }
    }
}