<?php

namespace metalguardian\i18n\controllers;

use metalguardian\i18n\models\SourceMessageForm;
use metalguardian\i18n\Module;
use Yii;
use metalguardian\i18n\models\SourceMessage;
use metalguardian\i18n\models\SourceMessageSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TranslationController implements the CRUD actions for SourceMessage model.
 */
class TranslationController extends Controller
{
    /**
     * Lists all SourceMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SourceMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing SourceMessage model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->populateMessages();

        if (Model::loadMultiple($model->messages, Yii::$app->request->post())) {
            $model->linkMessages();
            if (Model::validateMultiple($model->messages)) {
                Yii::$app->getSession()->setFlash('success', Module::t('Translation successfully updated'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Create SourceMessage item.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SourceMessageForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the SourceMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SourceMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SourceMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('The requested page does not exist.'));
        }
    }
}
