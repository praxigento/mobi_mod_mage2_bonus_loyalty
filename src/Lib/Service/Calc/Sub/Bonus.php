<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Praxigento\Bonus\Loyalty\Lib\Service\Calc\Sub;

use Praxigento\Bonus\Base\Lib\Entity\Compress;
use Praxigento\BonusLoyalty\Config as Cfg;
use Praxigento\Downline\Lib\Entity\Snap;
use Praxigento\Downline\Lib\Service\Snap\Request\ExpandMinimal as DownlineSnapExtendMinimalRequest;
use Praxigento\Pv\Lib\Entity\Sale as PvSale;

class Bonus {
    /** @var   \Praxigento\Downline\Lib\Service\ISnap */
    protected $_callDownlineSnap;
    /** @var Bonus\RankQualifier */
    protected $_rankQualifier;
    /** @var  \Praxigento\Downline\Lib\Tool\ITree */
    protected $_toolDownlineTree;
    /** @var  \Praxigento\Core\Lib\Tool\Format */
    protected $_toolFormat;

    /**
     * Bonus constructor.
     */
    public function __construct(
        \Praxigento\Downline\Lib\Service\ISnap $callDownlineSnap,
        \Praxigento\Core\Lib\Tool\Format $toolFormat,
        \Praxigento\Downline\Lib\Tool\ITree $toolDownlineTree,
        Bonus\RankQualifier $rankQualifier
    ) {
        $this->_callDownlineSnap = $callDownlineSnap;
        $this->_toolFormat = $toolFormat;
        $this->_toolDownlineTree = $toolDownlineTree;
        $this->_rankQualifier = $rankQualifier;
    }

    private function _expandTree($data) {
        $req = new DownlineSnapExtendMinimalRequest();
        $req->setKeyCustomerId(Compress::ATTR_CUSTOMER_ID);
        $req->setKeyParentId(Compress::ATTR_PARENT_ID);
        $req->setTree($data);
        $resp = $this->_callDownlineSnap->expandMinimal($req);
        return $resp->getSnapData();
    }

    /**
     * @param array $tree
     * @param array $orders
     * @param array $params configuration parameters ordered desc (from up to down)
     * @param array $percents
     *
     * @return array
     */
    public function calc($tree, $orders, $params, $percents) {
        $result = [ ];
        $mapTreeExp = $this->_expandTree($tree);
        $mapRankById = $this->_rankQualifier->qualifyCustomers($tree, $params);
        foreach($orders as $order) {
            $custId = $order[Cfg::E_SALE_ORDER_A_CUSTOMER_ID];
            $orderId = $order[PvSale::ATTR_SALE_ID];
            $pv = $order[PvSale::ATTR_TOTAL];
            $path = $mapTreeExp[$custId][Snap::ATTR_PATH];
            $parents = $this->_toolDownlineTree->getParentsFromPathReversed($path);
            $gen = 1;
            foreach($parents as $parentId) {
                if(isset($mapRankById[$parentId])) {
                    $parentRank = $mapRankById[$parentId];
                    if(isset($percents[$parentRank][$gen])) {
                        $percent = $percents[$parentRank][$gen];
                        $bonus = $pv * $percent;
                        $bonus = $this->_toolFormat->roundBonus($bonus);
                        $result[$parentId][$orderId] = $bonus;
                    }
                }
                $gen++;
            }

        }
        return $result;
    }
}