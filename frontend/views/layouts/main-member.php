<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <?php $this->head() ?>
    </head>
    <body class="hold-transition <?= \dmstr\helpers\AdminLteHelper::skinClass() ?>  sidebar-mini">
    <?php $this->beginBody() ?>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog" role="document">

                <div class="modal-body">
                    <!-- Construct the box with style you want. Here we are using box-success -->
                    <!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
                    <!-- The contextual class should match the box, so we are using direct-chat-success -->
                    <div class="box box-success direct-chat direct-chat-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Direct Chat</h3>
                            <div class="box-tools pull-right">
                                <span data-toggle="tooltip" title="3 New Messages" class="badge bg-red">3</span>
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <!-- In box-tools add this button if you intend to use the contacts pane -->
                                <button class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages">
                                <!-- Message. Default to the left -->
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">张晓龙</span>
                                        <span class="direct-chat-timestamp pull-right">2018-01-11 11:59</span>
                                    </div><!-- /.direct-chat-info -->
                                    <img class="direct-chat-img" src="<?=$directoryAsset?>/img/user1-128x128.jpg" alt="message user image"><!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        请问这是一个什么样的框架呢？
                                    </div><!-- /.direct-chat-text -->
                                </div><!-- /.direct-chat-msg -->

                                <!-- Message to the right -->
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-right">李白</span>
                                        <span class="direct-chat-timestamp pull-left">2018-01-11 12:00</span>
                                    </div><!-- /.direct-chat-info -->
                                    <img class="direct-chat-img" src="<?=$directoryAsset?>/img/user3-128x128.jpg" alt="message user image"><!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        只要你用它，你就会知道的
                                    </div><!-- /.direct-chat-text -->
                                </div><!-- /.direct-chat-msg -->
                            </div><!--/.direct-chat-messages-->

                            <!-- Contacts are loaded here -->
                            <div class="direct-chat-contacts">
                                <ul class="contacts-list">
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="<?=$directoryAsset?>/img/user1-128x128.jpg" alt="Contact Avatar">
                                            <div class="contacts-list-info">
              <span class="contacts-list-name">
                Count Dracula
                <small class="contacts-list-date pull-right">2/28/2015</small>
              </span>
                                                <span class="contacts-list-msg">How have you been? I was...</span>
                                            </div><!-- /.contacts-list-info -->
                                        </a>
                                    </li><!-- End Contact Item -->
                                </ul><!-- /.contatcts-list -->
                            </div><!-- /.direct-chat-pane -->
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <div class="input-group">
                                <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                <span class="input-group-btn">
        <button type="button" class="btn btn-success btn-flat">Send</button>
      </span>
                            </div>
                        </div><!-- /.box-footer-->
                    </div><!--/.direct-chat -->
                </div>
        </div>
    </div>


    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
