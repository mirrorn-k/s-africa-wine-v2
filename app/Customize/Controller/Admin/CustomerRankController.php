<?php
namespace Customize\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Eccube\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Customize\Entity\MtbCustomerRank;
use Customize\Repository\MtbCustomerRankRepository;
use Customize\Form\Type\Admin\MtbCustomerRankType;

class CustomerRankController extends AbstractController
{
    #[Route(path: '/%eccube_admin_route%/customer_rank', name: 'admin_customer_rank_index')]
    public function index(MtbCustomerRankRepository $rankRepository): Response
    {
        $ranks = $rankRepository->findAll();

        return $this->render('@default/admin/CustomerRank/index.twig',[
            'ranks' => $ranks,
        ]);
    }


    #[Route(path: '/%eccube_admin_route%/customer_rank/new', name: 'admin_customer_rank_new')]
    #[Route(path: '/%eccube_admin_route%/customer_rank/{id}/edit', name: 'admin_customer_rank_edit')]
    public function edit(Request $request, MtbCustomerRankRepository $rankRepository, $id = null): Response
    {
        $em = $this->entityManager;
        $rank = $id ? $rankRepository->find($id) : new MtbCustomerRank();

        // 編集時：DB値(0.03)をフォーム初期値として3に変換
        if ($rank->getDiscountRate() !== null) {
            $rank->setDiscountRate($rank->getDiscountRate() * 100);
        }

        $form = $this->createForm(MtbCustomerRankType::class, $rank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 保存前に 3 → 0.03 に変換
            if ($rank->getDiscountRate() !== null) {
                $rank->setDiscountRate($rank->getDiscountRate() / 100);
            }

            $em->persist($rank);
            $em->flush();

            $this->addSuccess('登録しました。', 'admin');
            return $this->redirectToRoute('admin_customer_rank_index');
        }

        return $this->render('@default/admin/CustomerRank/edit.twig', [
            'form' => $form->createView(),
            'rank' => $rank,
        ]);
    }
}
