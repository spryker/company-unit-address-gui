<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Controller;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CompanyUnitAddressGui\Communication\CompanyUnitAddressGuiCommunicationFactory getFactory()
 */
class EditCompanyUnitAddressController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\CompanyUnitAddressGui\Communication\Controller\ListCompanyUnitAddressController::indexAction()
     *
     * @var string
     */
    protected const COMPANY_UNIT_ADDRESS_LIST_URL = '/company-unit-address-gui/list-company-unit-address';

    /**
     * @var string
     */
    protected const PARAM_ID_COMPANY_UNIT_ADDRESS = 'id-company-unit-address';

    /**
     * @var string
     */
    protected const HEADER_REFERER = 'referer';

    /**
     * @var string
     */
    protected const MESSAGE_COMPANY_UNIT_ADDRESS_NOT_FOUND = 'Company unit address not found.';

    /**
     * @var string
     */
    protected const MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_SUCCESS = 'Company unit address has been successfully updated.';

    /**
     * @var string
     */
    protected const MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_ERROR = 'Company unit address update failed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCompanyUnitAddress = $this->castId($request->query->get(static::PARAM_ID_COMPANY_UNIT_ADDRESS));

        $companyUnitAddressForm = $this->getFactory()
            ->createCompanyUnitAddressForm($idCompanyUnitAddress)
            ->handleRequest($request);

        if (!$companyUnitAddressForm->getData()->getIdCompanyUnitAddress()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_NOT_FOUND);

            return $this->redirectResponse(static::COMPANY_UNIT_ADDRESS_LIST_URL);
        }

        if ($companyUnitAddressForm->isSubmitted()) {
            $this->updateCompanyUnitAddress($companyUnitAddressForm);

            return $this->redirectResponse((string)$request->headers->get(static::HEADER_REFERER));
        }

        $companyUnitAddressTransfer = $this->getFactory()
            ->getCompanyUnitAddressFacade()
            ->getCompanyUnitAddressById(
                (new CompanyUnitAddressTransfer())
                    ->setIdCompanyUnitAddress($idCompanyUnitAddress),
            );

        return $this->viewResponse([
            'companyUnitAddressForm' => $companyUnitAddressForm->createView(),
            'companyUnitAddress' => $companyUnitAddressTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUnitAddressForm
     *
     * @return void
     */
    protected function updateCompanyUnitAddress(FormInterface $companyUnitAddressForm): void
    {
        if (!$companyUnitAddressForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_ERROR);

            return;
        }

        $response = $this->getFactory()
            ->getCompanyUnitAddressFacade()
            ->update($companyUnitAddressForm->getData());

        if (!$response->getIsSuccessful()) {
            $this->addErrorMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_ERROR);

            return;
        }

        $this->addSuccessMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_SUCCESS);
    }
}
