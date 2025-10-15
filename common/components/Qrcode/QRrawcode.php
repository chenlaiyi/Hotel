<?php

namespace common\components\Qrcode;

use Exception;
use InvalidArgumentException;
use OutOfBoundsException;

include_once 'defines.php';
class QRrawcode
{
    public $version;
    public $datacode = array();
    public $ecccode = array();
    public $blocks;
    public $rsblocks = array(); //of RSblock
    public $count;
    public $dataLength;
    public $eccLength;
    public $b1;

    //----------------------------------------------------------------------
    public function __construct(QRinput $input)
    {
        $spec = array(0, 0, 0, 0, 0);

        $this->datacode = $input->getByteStream();
        if (is_null($this->datacode)) {
            throw new Exception('null imput string');
        }

        QRspec::getEccSpec($input->getVersion(), $input->getErrorCorrectionLevel(), $spec);

        $this->version = $input->getVersion();
        $this->b1 = QRspec::rsBlockNum1($spec);
        $this->dataLength = QRspec::rsDataLength($spec);
        $this->eccLength = QRspec::rsEccLength($spec);
        $this->ecccode = array_fill(0, $this->eccLength, 0);
        $this->blocks = QRspec::rsBlockNum($spec);

        $ret = $this->init($spec);
        if ($ret < 0) {
            throw new Exception('block alloc error');
            return null;
        }

        $this->count = 0;
    }

    //----------------------------------------------------------------------
    public function init(array $spec)
    {
        $dl = QRspec::rsDataCodes1($spec);
        $el = QRspec::rsEccCodes1($spec);
        $rs = QRrs::init_rs(8, 0x11d, 0, 1, $el, 255 - $dl - $el);


        $blockNo = 0;
        $dataPos = 0;
        $eccPos = 0;
        for ($i = 0; $i < QRspec::rsBlockNum1($spec); $i++) {
            $ecc = array_slice($this->ecccode, $eccPos);
            $this->rsblocks[$blockNo] = new QRrsblock($dl, array_slice($this->datacode, $dataPos), $el, $ecc, $rs);
            $this->ecccode = array_merge(array_slice($this->ecccode, 0, $eccPos), $ecc);

            $dataPos += $dl;
            $eccPos += $el;
            $blockNo++;
        }

        if (QRspec::rsBlockNum2($spec) == 0)
            return 0;

        $dl = QRspec::rsDataCodes2($spec);
        $el = QRspec::rsEccCodes2($spec);
        $rs = QRrs::init_rs(8, 0x11d, 0, 1, $el, 255 - $dl - $el);

        if ($rs == NULL) return -1;

        for ($i = 0; $i < QRspec::rsBlockNum2($spec); $i++) {
            $ecc = array_slice($this->ecccode, $eccPos);
            $this->rsblocks[$blockNo] = new QRrsblock($dl, array_slice($this->datacode, $dataPos), $el, $ecc, $rs);
            $this->ecccode = array_merge(array_slice($this->ecccode, 0, $eccPos), $ecc);

            $dataPos += $dl;
            $eccPos += $el;
            $blockNo++;
        }

        return 0;
    }

    //----------------------------------------------------------------------
    public function getCode()
    {
        if ($this->count < $this->dataLength) {
            $row = $this->count % $this->blocks;
            $col = $this->count / $this->blocks;
            if ($col >= $this->rsblocks[0]->dataLength) {
                $row += $this->b1;
            }
            // 获取行索引对应的块数据
            $rowData = $this->rsblocks[$row] ?? null;

            if ($rowData === null) {
                // 处理行索引不存在的情况
                $ret = null;
            } else {
                $ret = $rowData->data[$col] ?? null;
            }
        } else if ($this->count < $this->dataLength + $this->eccLength) {
            $row = ($this->count - $this->dataLength) % $this->blocks;
            $col = ($this->count - $this->dataLength) / $this->blocks;
            // 确保 $row 和 $col 是整数类型
            if (!is_int($row) || !is_int($col)) {
                throw new InvalidArgumentException('Row and column indices must be integers.');
            }

            // 确保 $row 和 $col 在有效范围内
            if ($row < 0 || $row >= count($this->rsblocks) || $col < 0 || $col >= count($this->rsblocks[$row]->ecc)) {
                throw new OutOfBoundsException('Row or column index is out of bounds.');
            }

            // 确保 $this->rsblocks[$row] 和 $this->rsblocks[$row]->ecc 不为 null
            if (!isset($this->rsblocks[$row]) || !isset($this->rsblocks[$row]->ecc)) {
                throw new InvalidArgumentException('Invalid rsblocks structure.');
            }

            // 获取结果
            $ret = $this->rsblocks[$row]->ecc[$col];

        } else {
            return 0;
        }
        $this->count++;

        return $ret;
    }
}