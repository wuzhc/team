<?php
/**
 * Created by PhpStorm.
 * User: wuzc
 * Date: 18-1-1
 * Time: 下午12:43
 */

namespace console\controllers;


use common\models\Goods;
use common\models\GoodsAttributeValueMap;
use common\models\Product;
use Yii;
use yii\console\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqController extends Controller
{

    /**
     * 例子一 hello world
     * 消费者
     */
    public function actionHelloWorldSend()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        echo " [x] Sent 'Hello World!'\n";

        $channel->close();
        $connection->close();
    }

    /**
     * 例子一 hello world
     * 消费者
     */
    public function actionHelloWorldRecv()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $callback = function ($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    /**
     * 例子二 ： worker queue
     * 发送者
     */
    public function actionWorkQueueSend()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('work_queue', false, false, false, false);

        global $argv;
        $msg = new AMQPMessage($argv[2]);
        $channel->basic_publish($msg, '', 'work_queue');

        echo " [x] Sent '$argv[2]!'\n";

        $channel->close();
        $connection->close();
    }

    /**
     * 例子二： worker queue
     * 消费者
     */
    public function actionWorkQueueConsume()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('send_email', false, false, false, false);

        echo " [x] You can stop with Ctrl+C \n";

        $callback = function ($msg) {
            $seconds = substr_count($msg->body, '.');
            echo " [x] Wait ", $msg->body, ' ', $seconds, " seconds\n";
            sleep($seconds);
            echo " [x] Done \n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('send_email', '', false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }


    static $words = "《中华人民共和国核安全法》自2018年1月1日起正式实施。核安全法共八章、94条，分为总则、核设施安全、核材料和放射性废物安全、核事故应急、信息公开和公众参与、监督检查、法律责任、附则。按照确保安全的方针，核安全法确立严格的标准、严密的制度、严格的监管和严厉的处罚。这四个“严”也成为这部法律的最大亮点。5、环保税为企业排污戴上“紧箍咒”《中华人民共和国环境保护税法》自2018年1月1日起施行。规定征收环境保护税，不再征收排污费。该法明确，直接向环境排放应税污染物的企业事业单位和其他生产经营者为环境保护税的纳税人，应当依照规定缴纳环境保护税。应税大气污染物的税额幅度为每污染当量1.2元—12元，水污染物的税额幅度为每污染当量1.4元—14元，固体废物按不同种类每吨5元—1000元不等，工业噪声按超标分贝数，每月按350元—11200元缴纳。6、惩罚污水排放行为的力度加大新修订的《中华人民共和国水污染防治法》自2018年1月1日起施行。新版水污染防治法明确了各级政府的水￥环境质量责任，增加省、市、县、乡建立河长制，明确“国家对重点水污染物排放实施总量控制制度。”“对超过重点水污染物排放总量控制指标或者未完成水环境质量改善目标的地区，省级以上人民政府环境保护主管部门应当会同有关部门约谈该地区人民政府的主要负责人，并暂停审批新增重点水污染物排放总量的建设项目的环境影响评价文件。约谈情况应当向社会公开。”7、部分城市驾考科目二增加停车取卡有网络传言称，“2018年1月1日起，驾照考试科目二将增加停车取卡项目”，事实上这只在浙江杭州等部分城市实施。依据《机动车驾驶人测验内容和方法》的划定，省级公安机关交通管理部门可依据实际增加考试内容。8、新出厂汽车内都应有反光背心新修订的国家标准《机动车运行安全技术条件》（GB7258-2017）将于2018年1月1日起开始实施。新标准明确“汽车应配备1件反光背心”。公安部交通管理科研所辟谣表示，此前网传“如果你的爱车不配备反光背心，年检都过不了”的说法属于误读。该规定只适用于2018年1月1日起新出厂的汽车，要求汽车生产厂家在新车出厂时配备反光背心，不需要车主或者驾驶人自行购买。其目的是保证汽车在夜间故障时，提升驾驶人下车时的可视认性。9、新能源汽车将免征车辆购置税财政部、税务总局、工业和信息化部、科技部发布关于免征新能源汽车车辆购置税的公告，自2018年1月1日至2020年12月31日，对购置的新能源汽车免征车辆购置税10、小排量汽车购置税将恢复到10%自2018年1月1日起，按照《中华人民共和国车辆购置税暂行条例》规定，1.6L及以下排量汽车恢复按10%的法定税率征收车辆购置税。此前，出于对小排量汽车的发展支持，国家出台政策，对小排量汽车购置税进行优惠。11、贷款买新能源汽车自用，首付仅需15%中国人民银行、中国银行业监督管理委员联合发布的《关于调整汽车贷款有关政策的通知》自2018年1月1日起实施。其中传统动力汽车贷款最高发放比例不变，自用车贷款的金额不得超过借款人所购汽车价格的80%。而二手车贷款最高发放比例从50%调整为70%。新增加的新能源汽车贷款条例明确，自用新能源汽车贷款最高发放比例为85%，商用新能源汽车贷款最高发放比例为75%。11、修订后的《中华人民共和国中小企业促进法》实施修订后的《中华人民共和国中小企业促进法》将于2018年1月1日起正式施行。新法将近年来行之有效的政策上升为法律，规范了财税支持相关政策，完善了融资促进相关措施，特别是规范行政许可事项、减轻企业负担、减化小微企业税收征管和注销登记程序，为中小企业发展创造良好环境。12、部分小微企业继续免征增值税《财政部税务总局关于延续小微企业增值税政策的通知》（财税〔2017〕76号）规定：为支持小微企业发展，自2018年1月1日至2020年12月31日，继续对月销售额2万元（含本数）至3万元的增值税小规模纳税人，免征增值税13、增值税发票代码将增至12位近年来，增值税普通发票的种类和使用量增加，10位发票代码难以满足纳税人需要。2018年1月1日开始，我国将对增值税普通发票进行调整，新版增值税普通发票(折叠票)发票代码从现行的10位调整为12位。14、法官法、公务员法等八部法律将进行修改第十二届全国人民代表大会常务委员会第二十九次会议决定：对《中华人民共和国法官法》《中华人民共和国检察官法》《中华人民共和国公务员法》《中华人民共和国律师法》《中华人民共和国公证法》《中华人民共和国仲裁法》《中华人民共和国行政复议法》《中华人民共和国行政处罚法》进行修改，该决定自2018年1月1日起施行。5、新修订的《中华人民共和国标准化法》实施新修订的《中华人民共和国标准化法》将于2018年1月1日开始施行。新法制定法规目标更明确，突出了对质量安全的要求。明确保障人身健康和生命财产安全，维护国家安全、生态环境安全是制定法规的宗旨。16、实行地图审核制度 杜绝错误信息新修订的《地图审核管理规定》将于2018年1月1日起正式施行。其中明确，“国家实行地图审核制度。向社会公开的地图，应当报送有审核权的测绘地理信息主管部门审核。”17、取消钢材、绿泥石等产品出口关税国务院关税税则委员会印发的2018年关税调整方案称，自2018年1月1日起，我国将取消钢材、绿泥石等产品出口关税，并适当降低三元复合肥、磷灰石、煤焦油、木片、硅铬铁、钢坯等产品出口关税。18、BIM领域首份细则性国标将实施建筑信息模型(BIM)领域首份细则性国家标准《建筑信息模型施工应用标准》，将于2018年1月1日起实施。该《标准》是我国第一部建筑工程施工领域的BIM应用标准，填补了我国BIM技术应用标准的空白。BIM（Building Information Modeling，建筑信息模型）是一种利用信息技术和数字模型对建设工程项目进行设计、施工、运营管理的方法，业界把BIM技术视为CAD之后建筑行业的第二次革命。大到摩天大楼，小到螺丝钉，都可以通过BIM技术虚拟呈现。";

    public function actionSave()
    {
        if ($goodsID = $this->_saveGoods()) {
            $this->_saveGoodsAttr(rand(1, 10), $goodsID);
            $this->_saveProduct(rand(1, 5), $goodsID);
            echo "goods $goodsID insert success \n";
        } else {
            echo "failed \n";
        }
    }

    private function _saveProduct($n, $goodsID)
    {
        $temp = [];
        for ($i = 0; $i < $n; $i++) {
            $r = rand(1, 365);
            $temp[] = [
                $goodsID,
                date('Y-m-d H:i:s', strtotime("-$r days")),
                1,
                mb_substr(self::$words, rand(0, round(6500 / 3)), rand(20, 50)),
                'ZC_' . rand(1000, 9999),
                rand(111, 999),
                rand(111, 999),
                rand(111, 999),
                rand(1000, 9000),
                'http://images2015.cnblogs.com/blog/443934/201707/443934-20170720223242380-719017232.png'
            ];
        }
        return Yii::$app->db->createCommand()->batchInsert(Product::tableName(), [
            'fdGoodsID',
            'fdCreate',
            'fdStatus',
            'fdTitle',
            'fdNo',
            'fdSellPrice',
            'fdMarketPrice',
            'fdCostPrice',
            'fdStore',
            'fdCover',
        ], $temp)->execute();
    }

    /**
     * 保存商品属性值
     * @param $n
     * @param $goodsID
     * @return int
     */
    private function _saveGoodsAttr($n, $goodsID)
    {

        $goodsAttr = [];
        for ($i = 0; $i < $n; $i++) {
            $goodsAttr[] = [
                round(1, 10),
                $i + 1,
                $goodsID
            ];
        }

        return Yii::$app->db->createCommand()->batchInsert(GoodsAttributeValueMap::tableName(), [
            'fdAttributeValueID',
            'fdAttributeID',
            'fdGoodsID'
        ], $goodsAttr)->execute();
    }

    /**
     * @return int
     */
    private function _saveGoods()
    {
        $goods = new Goods();
        $rand = rand(1, 720);
        $goods->fdUserID = rand(1, 10);
        $goods->fdTitle = mb_substr(self::$words, rand(0, round(6500 / 3)), rand(20, 50));
        $goods->fdCategoryID = rand(1, 8);
        $goods->fdStatus = 1;
        $goods->fdCreate = date('Y-m-d H:i:s', strtotime("-$rand days"));
        $goods->fdNo = 'ZC' . $rand;
        $res = $goods->save();
        return $res ? $goods->id : 0;
    }

    public function actionSend()
    {
        $conn = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $conn->channel();

        $channel->queue_declare('goods', false, false, false, false);

        $msg = new AMQPMessage("goods");
        $channel->basic_publish($msg, '', 'goods');

        echo " [x] Sent 'Goods!'\n";

        $channel->close();
        $conn->close();
    }

    public function actionRecv()
    {
        $conn = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $conn->channel();

        $channel->queue_declare('goods', false, false, false, false);

        $obj = $this;
        $callback = function ($msg) use ($obj) {
            echo " [x] Received ", $msg->body, "\n";
            $obj->actionSave();
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        $channel->basic_consume('goods', '', false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $conn->close();
    }
}