<?php

namespace Amasty\SnitkoArtur\Cron;

use Amasty\SnitkoArtur\Model\BlacklistRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class SendBlacklistEmail
{

    /**
     * @var BlacklistRepository
     */
    protected $blacklistRepository;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     *  @var FactoryInterface
     */
    private $templateFactory;

    /**
     *  @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;



    public function __construct
    (
        BlacklistRepository $blacklistRepository,
        ScopeConfigInterface $scopeConfig,
        FactoryInterface $templateFactory,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager
    )
    {
        $this->blacklistRepository = $blacklistRepository;
        $this->scopeConfig = $scopeConfig;
        $this->templateFactory = $templateFactory;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
    }

    public function execute() {
        $firstProductSku = '24-MB01';
        $columnName = 'Mail';
        $blacklist_product = $this->blacklistRepository->getBySku($firstProductSku);

        $senderName = "admin";
        $senderEmail = "amastyadmin@amasty.com";
        $templateId = $this->scopeConfig->getValue('artur_module_config/cron_options/email_template') ;
        $toEmail = $this->scopeConfig->getValue('artur_module_config/cron_options/client_email');

        $templateVars = [
            'blacklist_product' => $blacklist_product,
            'product_sku' => $blacklist_product->getSku(),
            'product_qty' => $blacklist_product->getQty()
        ];
        $storeId = $this->storeManager->getStore()->getId();
        $from = [
            'email' => $senderEmail,
            'name' => $senderName
        ];
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId
        ];

        /**
         * @var \Magento\Email\Model\Transport $transport;
         */
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId, ScopeInterface::SCOPE_STORE)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFromByScope($from)
            ->addTo($toEmail)
            ->getTransport();
//        $transport->sendMessage();

        $template = $this->templateFactory->get($templateId);
        $template->setVars($templateVars)->setOptions($templateOptions);
        $emailBody = $template->processTemplate();

        $this->blacklistRepository->setDataInProduct($firstProductSku, $columnName, $emailBody);
    }
}
