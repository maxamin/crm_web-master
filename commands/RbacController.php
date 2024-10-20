<?php

namespace app\commands;

use app\models\Users;
use app\rbac\ManageBranchContactRule;
use Yii;
use yii\console\Controller;


class RbacController extends Controller
{
    const ADMINS = ['m.chubin', 'admin', 'mani123lena', 'us770770'];

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';

        $general = $auth->createRole('general');
        $general->description = 'Менеджер ГО';

        $branchChief = $auth->createRole('branchChief');
        $branchChief->description = 'Начальник отделения';

        $manager = $auth->createRole('manager');
        $manager->description = 'Менеджер';

        $auth->add($admin);
        $auth->add($general);
        $auth->add($branchChief);
        $auth->add($manager);

        $manageContact = $auth->createPermission('manageContact');
        $manageContact->description = 'Управление контактом';

        $importContacts = $auth->createPermission('importContacts');
        $importContacts->description = 'Импорт контактов';

        $manageBranchContactRule = new ManageBranchContactRule();

        $auth->add($manageBranchContactRule);

        $manageBranchContact = $auth->createPermission('manageBranchContact');
        $manageBranchContact->description = 'Управление контактом отделения';

        $manageBranchContact->ruleName = $manageBranchContactRule->name;

        $auth->add($manageContact);
        $auth->add($importContacts);
        $auth->add($manageBranchContact);

        $manageAllContacts = $auth->createPermission('manageAllContacts');
        $manageAllContacts->description = 'Управление всеми контактами';

        $manageAllLeads = $auth->createPermission('manageAllLeads');
        $manageAllLeads->description = 'Управление всеми зделками';

        $manageAllTasks = $auth->createPermission('manageAllTasks');
        $manageAllTasks->description = 'Управление всеми задачами';

        $auth->add($manageAllContacts);
        $auth->add($manageAllLeads);
        $auth->add($manageAllTasks);

        $auth->addChild($manager, $manageBranchContact);
        $auth->addChild($branchChief, $importContacts);
        $auth->addChild($general, $manageContact);
        $auth->addChild($general, $manageAllContacts);
        $auth->addChild($general, $manageAllLeads);
        $auth->addChild($general, $manageAllTasks);

        $auth->addChild($admin, $general);
        $auth->addChild($general, $branchChief);
        $auth->addChild($branchChief, $manager);

        $auth->addChild($manageBranchContact, $manageContact);
    }


    public function actionAssign()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAllAssignments();

        $manager = $auth->getRole('manager');
        $general = $auth->getRole('general');
        $admin = $auth->getRole('admin');

        $users = Users::find()->joinWith(['profile' => function ($q) {
            $q->joinWith('branch');
        }])->all();

        foreach ($users as $user) {

            if ($this->checkIsAdmin($user)) {
                $auth->assign($admin, $user->id);
                continue;
            }

            switch ($user->profile->branch->is_main) {
                case 1:
                    $auth->assign($general, $user->id);
                    break;
                default:
                    $auth->assign($manager, $user->id);
            };
        }
    }

    private function checkIsAdmin($user)
    {
        return in_array($user->username, self::ADMINS);
    }
}