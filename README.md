# GridToSessionBehavior
This is yii2 controller behavior. 
It can save grid filters, page and sort in session and restore when user return to action with grid.

example for using this behavior in controller:

 ```php
/**
* Lists all Document models.
* @param integer $mode in this case this parameter is indicator for restore params from session
* @return mixed
*/
public function actionIndex($mode=null)
{
    $searchModel = new DocumentSearch();
    if(!$mode) {
        $this->loadParams();
        $mode = Yii::$app->request->get('mode');
    }
    $this->saveParams('DocumentSearch',['mode'=>1]);    
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);        

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,        
    ]);
}
 ```