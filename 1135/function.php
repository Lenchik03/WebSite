<?php
// Включаем строгую типизацию
declare(strict_types=1);

/**
 * @param $some
 * отладочная функция
 */
function dd($some){
    echo '<pre>';
    print_r($some);
    echo '</pre>';
    exit();
}

/**
 * @param $url
 * редирект на указаный URL
 */
function goUrl(string $url){
    echo '<script type="text/javascript">location="';
    echo $url;
    echo '";</script>';
}

/**
 * функция возвращает масив статей
 * @return array
 */
function getArticles() : array
{
    return json_decode(file_get_contents('articles.json'), true);
}

function storeArticlesInToFile(array $articles)
{
    $json = json_encode($articles);
    file_put_contents('articles.json', $json);
}

function getCarouselImage() : array
{
    return json_decode(file_get_contents('carousel.json'), true);
}

/**
 * функция возвращает статью  в виде масива по id
 * @param int $id
 * @return array
 */
function getArticleById(int $id):array
{
    $articleList =getArticles();
    $curentArticle = [];
    if (array_key_exists($id, $articleList)) {
        $curentArticle = $articleList[$id];
    }
    //dd($curentArticle);
    return $curentArticle;
}

/**
 * функция генерирует список <li> из Json
 * и формирует ссылки вида URI index.php?id=1
 *
 * @return string
 */
function getArticleList(): string
{
    $articles = getArticles();
    $link = '';
    foreach ($articles as $article) {
        $link .= '<li class="nav-item"><a class="nav-link" href="index.php?id='. $article['id']
            . '">'. $article['title']. '</a></li>';
    }
    return $link;
}

//function renderFullArticleHTML($article)
//{
//    $content = '<div class="col-5">
//            <img src="image/'.$article['image'].'" alt="...">
//        </div>
//        <div class="col-5">
//            <h2>'.$article['title'].'</h2>
//            <p>'.$article['content'].'</p>
//        </div>';
//    return $content;
//}

//function renderFullArticle(int $id)
//{
//    $res_art = null;
//    $articles = getArticles();
//    $article = getArticleById($id);
//    $res_art = renderFullArticleHTML($article);
//    return $res_art;
//}

function renderArticleCard(array $article, $single = false) : string
{
    //$content ='';
    $content ='
            <div class="card shadow-sm col-3 m-2">
                <img class="bd-placeholder-img card-img-top" src="/image/'. $article['image'] .'" alt="dcddds" width="100%" height="225px">
                <div class="card-body">
                    <p class="card-text">'.$article['title'].'</p>
                    <div class="d-flex justify-content-between align-items-center">
                   ';

    if($single == true)
    {
        $content .= '<p>'.$article['content']. '</p>';
    }
    else{
        $content .= '<div class="btn-group">
                            <a href="index.php?id='. $article['id'].'" class="btn btn-sm btn-outline-secondary">Посмотреть</a> 
                        </div>';
    }

    $content .= '</div>
            </div>
</div>';
    return $content;
}

function renderArticlesCardList()
{
    $articles = getArticles();
    $article_list = '';
    foreach ($articles as $article) {
        $article_list .= renderArticleCard($article);
    }
    return $article_list;
}

function index():string
{
    if(isset($_GET['id']))
    {
        $id = (int)$_GET['id'];
        $article = getArticleById($id);
    }
    else{
        $article = '';
    }

    if(empty($article))
    {
        $content = renderArticlesCardList();
    }
    else{
        $content = renderArticleCard($article, true);
    }
    return $content;
}


//function renderCalculatorHtml()
//{
//    $content = '<form action="" method="post" class="calculate-form">
//    <input type="text" name="x"  placeholder="Первое число">
//    <select class="operations" name="operation">
//        <option value="+"> + </option>
//        <option value="-"> - </option>
//        <option value="*"> * </option>
//        <option value="/"> / </option>
//    </select>
//    <input type="text" name="y" placeholder="Второе число">
//
//    <input class="submit_form" type="submit" name="submit" value="Получить ответ">
//</form>';
//    renderCalculator('x','y','operation');
//    return $content;
//}

function renderCalculator($number1, $number2,$oper)
{
    //renderCalculatorHtml();
    $message='';
    $operation = null;
    if (isset($_POST['submit'])) {
        $x = $_POST[$number1];
        $y = $_POST[$number2];
        $operation = $_POST[$oper];
    }

    if(!$operation || (!$x && $x != '0') || (!$y &&$y != '0')) {
        $error_result = 'Не все поля заполнены';
    }
    else {
        if(!is_numeric($x) || !is_numeric($y)) {
            $error_result = "Операнды должны быть числами";
        }
        else
            switch($operation){
                case '+':
                    $result = $x + $y;
                    $message = $x.'+'.$y.'='.$result;
                    break;
                case '-':
                    $result = $x - $y;
                    $message = $x.'-'.$y.'='.$result;
                    break;
                case '*':
                    $result = $x * $y;
                    $message = $x.'*'.$y.'='.$result;
                    break;
                case '/':
                    if( $y == '0')
                        $error_result = "На ноль делить нельзя!";
                    else
                        $result = $x / $y;
                    $message = $x.'/'.$y.'='.$result;
                    break;
            }
    }
    if(isset($error_result)) {
        return $error_result;
    }
    else {
        return $message;
    }



}


function renderCarouselImage($image)
{
    $content = '<div class="carousel-item active">
                        <img src="./image/'.$image['image'].'" class="d-block w-100" style="height: 800px;" alt="...">
                </div>';

    return $content;
}

function renderCarouselImageList()
{
    $images = getCarouselImage();
    $image_list = '';
    foreach ($images as $image)
    {
        $image_list .= renderCarouselImage($image);
    }
    return $image_list;

}

function renderAddArticleForm(){
    return '<form action="adminko.php" method="get">
  <div class="mb-3">
    <label class="form-label">Название</label>
    <input type="text" name="title" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Картинка</label>
    <input type="text" name="image" class="form-control" >
  </div>
  <div class="form-floating">
  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="content"></textarea>
  <label for="floatingTextarea">Comments</label>
</div>
<input type="hidden" name="act" value="store">
  <button type="submit" class="btn btn-primary">Submit</button>
</form>';
}

function renderEditArticleForm(array $article){
    return '<form action="adminko.php" method="get">
  <div class="mb-3">
    <label class="form-label">Название</label>
    <input type="text" name="title" class="form-control" value="'.$article['title'].'">
  </div>
  <div class="mb-3">
    <label class="form-label">Картинка</label>
    <input type="text" name="image" class="form-control" value="'.$article['image'].'">
  </div>
  <div class="form-floating">
  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="content">
  '.$article['content'].'
</textarea>
  <label for="floatingTextarea">Comments</label>
</div>
<input type="hidden" name="act" value="update">
<input type="hidden" name="id" value="'.$article['id'].'">
  <button type="submit" class="btn btn-primary">Update</button>
</form>';
}

function renderArticleList(): string
{
    $articles = getArticles();
    $link = '<a href="adminko.php?act=add" class="btn btn-primary">Add</a>';
    foreach ($articles as $article) {
        $link .= '<a class="nav-link" href="adminko.php?act=edit&id='. $article['id']
            . '">'. $article['title']. '</a>';
    }
    return $link;
}

function articleStore()
{
    $article =[];
    $articles = getArticles();
    $new_id = count($articles)+1;
    if(!empty($_REQUEST['title']) && !empty($_REQUEST['image'])&& !empty($_REQUEST['content'])  ){
        $article['id'] = $new_id;
        $article['title'] = $_REQUEST['title'];
        $article['image'] = $_REQUEST['image'];
        $article['content'] = $_REQUEST['content'];
        $articles[$new_id] = $article;
        storeArticlesInToFile($articles);
        goUrl('adminko.php');
    }
}

function articleUpdate()
{
    $article =[];
    $articles = getArticles();
    //$new_id = count($articles)+1;
    if(!empty($_REQUEST['title']) && !empty($_REQUEST['image'])&& !empty($_REQUEST['content'])  ){
        $article['id'] = $_REQUEST['id'];
        $article['title'] = $_REQUEST['title'];
        $article['image'] = $_REQUEST['image'];
        $article['content'] = $_REQUEST['content'];
        $articles[$_REQUEST['id']] = $article;
        storeArticlesInToFile($articles);
        goUrl('adminko.php');
    }
}

function adminMain()
{
    if(isset($_REQUEST['act'])){
        switch ($_REQUEST['act']){
            case "store":
                articleStore();
                break;
            case "add":
                echo renderAddArticleForm();
                break;
            case "update":
                articleUpdate();
                break;
            case "edit":
                if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
                    $article = getArticleById( (int) $_REQUEST['id']);
                    echo renderEditArticleForm($article);
                }
                break;
        }
    }
    else{
        echo renderArticleList();
    }
}