<?php

namespace app\rbac;

use app\models\Contacts;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;


class ManageBranchContactRule extends Rule
{
    public $name = 'isBranchContact';
    /**
     * Executes the rule.
     *
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest && isset($params['contact'])) {

            /** @var Contacts $contact */
            $contact = $params['contact'];

            switch (Yii::$app->user->identity->profile->branch->id) {

                case $contact->rUser->profile->branch->id:
                    return true;
                    break;
                case $contact->cUser->profile->branch->id:
                    return true;
                    break;
                default:
                    return false;

            }

        }

        return false;
    }
}