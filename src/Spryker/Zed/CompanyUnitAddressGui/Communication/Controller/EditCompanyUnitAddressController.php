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
    const URL_PARAM_ID_COMPANY_UNIT_ADDRESS = 'id-company-unit-address';
    const MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_SUCCESS = 'Company unit address has been successfully updated.';
    const MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_ERROR = 'Company unit address update failed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCompanyUnitAddress = $this->castId($request->query->get(static::URL_PARAM_ID_COMPANY_UNIT_ADDRESS));

        $companyUnitAddressForm = $this->getFactory()
            ->createCompanyUnitAddressForm($idCompanyUnitAddress)
            ->handleRequest($request);

        if ($companyUnitAddressForm->isSubmitted()) {
            $this->updateCompanyUnitAddress($companyUnitAddressForm);
            return $this->redirectResponse($request->headers->get('referer'));
        }

        $companyUnitAddressTransfer = $this->getFactory()
            ->getCompanyUnitAddressFacade()
            ->getCompanyUnitAddressById(
                $this->createCompanyUnitAddressTransfer($idCompanyUnitAddress)
            )->getCompanyUnitAddressTransfer();

        return $this->viewResponse([
            'idCompanyUnitAddress' => $idCompanyUnitAddress,
            'companyUnitAddressForm' => $companyUnitAddressForm->createView(),
            'companyUnitAddress' => $companyUnitAddressTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyUnitAddressForm
     *
     * @return void
     */
    protected function updateCompanyUnitAddress(FormInterface $companyUnitAddressForm)
    {
        if ($companyUnitAddressForm->isSubmitted() && $companyUnitAddressForm->isValid()) {
            $response = $this->getFactory()
                ->getCompanyUnitAddressFacade()
                ->update($companyUnitAddressForm->getData());
            if ($response->getIsSuccessful()) {
                $this->addSuccessMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_SUCCESS);

                return;
            }
        }

        $this->addErrorMessage(static::MESSAGE_COMPANY_UNIT_ADDRESS_UPDATE_ERROR);
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function createCompanyUnitAddressTransfer(int $idCompanyUnitAddress)
    {
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        return $companyUnitAddressTransfer;
    }
}
