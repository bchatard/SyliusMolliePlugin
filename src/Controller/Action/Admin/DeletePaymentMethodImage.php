<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusMolliePlugin\Controller\Action\Admin;

use BitBag\SyliusMolliePlugin\Entity\MollieGatewayConfigInterface;
use BitBag\SyliusMolliePlugin\Logger\MollieLoggerActionInterface;
use BitBag\SyliusMolliePlugin\Uploader\PaymentMethodLogoUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DeletePaymentMethodImage
{
    /** @var PaymentMethodLogoUploaderInterface */
    private $logoUploader;

    /** @var RepositoryInterface */
    private $logoRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var MollieLoggerActionInterface */
    private $loggerAction;

    public function __construct(
        PaymentMethodLogoUploaderInterface $logoUploader,
        RepositoryInterface $logoRepository,
        EntityManagerInterface $entityManager,
        MollieLoggerActionInterface $loggerAction
    ) {
        $this->logoUploader = $logoUploader;
        $this->logoRepository = $logoRepository;
        $this->entityManager = $entityManager;
        $this->loggerAction = $loggerAction;
    }

    public function __invoke(Request $request)
    {
        $methodName = $request->request->get('method');

        /** @var MollieGatewayConfigInterface $mollieGateway */
        $mollieGateway = $this->logoRepository->findOneBy(['name' => $methodName]);

        $this->logoUploader->remove($mollieGateway->getCustomizeMethodImage()->getPath());
        $mollieGateway->setCustomizeMethodImage(null);

        $this->entityManager->persist($mollieGateway);
        $this->entityManager->flush();

        $this->loggerAction->addLog(sprintf('Deleted image from method'));

        return new Response('OK', Response::HTTP_OK);
    }
}
