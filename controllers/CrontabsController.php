<?php

namespace deluxcms\crontab\controllers;

use Yii;
use deluxcms\crontab\models\Crontabs;
use deluxcms\crontab\models\CrontabsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrontabsController implements the CRUD actions for Crontabs model.
 */
class CrontabsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Crontabs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CrontabsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Crontabs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Crontabs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Crontabs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Crontabs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Crontabs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Crontabs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Crontabs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Crontabs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 查看系统的crontab
     */
    public function actionSystemCrontabList()
    {
        $systemList = Yii::$app->request->post('system-list', '');
        $status = '';
        if (Yii::$app->request->isPost) {
            if (Yii::$app->crontabManager->write($systemList)) {
                $status = 'has-success';
            } else {
                $status = 'has-error';
            }
        }
        return $this->render('system_crontab_list', ['status' => $status]);
    }
    
    /**
     * 将所有crontab写入系统中
     */
    public function actionWriteCrontabs()
    {
        if (Yii::$app->crontabManager->writeToSystem()) {
            Yii::$app->session->setFlash('success', '写入crontab成功');
        } else {
            Yii::$app->session->setFlash('error', '写入crontab失败');
        }
        return $this->redirect(['index']);
    }

    /**
     * 还原系统crontab
     */
    public function actionRestoreCrontabs()
    {
        if (Yii::$app->crontabManager->restoreSystemCrontab()) {
            Yii::$app->session->setFlash('success', '写入crontab成功');
        } else {
            Yii::$app->session->setFlash('error', '写入crontab失败');
        }
        return $this->redirect(['system-crontab-list']);
    }

    /**
     * 将所有crontab写入系统中
     */
    public function actionStartPhpdeamon()
    {
        if (Yii::$app->crontabManager->restoreSystemCrontab() && Yii::$app->crontabManager->writeToSystem(True)) {
            Yii::$app->session->setFlash('success', '写入crontab成功');
        } else {
            Yii::$app->session->setFlash('error', '写入crontab失败');
        }
        return $this->redirect(['index']);
    }

}
