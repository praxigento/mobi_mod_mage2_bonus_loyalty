<?php
/**
 * User: Alex Gusev <alex@flancer64.com>
 */

namespace Praxigento\BonusLoyalty\Repo;

interface IModule
{

    /**
     * @return array [$rankId=>[$generation=>$percent], ...]
     */
    function getBonusPercents();

    /**
     * Get compressed tree with qualification data (PV, GV, PSAA, ...).
     *
     * @param $calcId
     *
     * @return array [[Compress/customerId+parentId, Qualification/pv+gv+psaa], ...]
     */
    public function getCompressedTreeWithQualifications($calcId);

    /**
     * Configuration parameters ordered from up to down.
     *
     * @return array [[Cfg\Param/*], ...]
     */
    function getConfigParams();

    /**
     * @param string $dsFrom 'YYYYMMDD'
     * @param string $dsTo 'YYYYMMDD'
     *
     * @return array [$custId => $pvSummary, ...]
     */
    function getQualificationData($dsFrom, $dsTo);

    /**
     * @param string $dsFrom 'YYYYMMDD'
     * @param string $dsTo 'YYYYMMDD'
     *
     * @return array [[$custId, $saleId, $pvTotal], ...]
     */
    function getSalesOrdersForPeriod($dsFrom, $dsTo);

    /**
     * @param array $updates [$custId=>[$orderId=>$amount], ...]
     *
     * @return
     */
    public function saveBonus($updates);

    /**
     * Register bonus transactions for sale orders.
     *
     * @param array $updates [$transId=>$saleId, ...]
     */
    public function saveLogSaleOrders($updates);

    /**
     * @param array $updates [[Qualification/*], ...]
     */
    public function saveQualificationParams($updates);

}