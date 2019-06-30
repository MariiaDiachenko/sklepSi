<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\AdminUserType;

/**
 * User Controller Class
 */
class UserController extends Controller
{
    /**
     * @Route("/admin/user/", name="user_index", methods={"GET"})
     *
     * @param Request            $request
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $userRepository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render('user/index.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * @Route("/user/new", name="user_new", methods={"GET","POST"})
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $formType = $this->isGranted(USER::ROLE_ADMIN) ? AdminUserType::class : UserType::class;
        $form = $this->createForm($formType, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $entityManager = $this->getDoctrine()->getManager();
          if ($this->isGranted(USER::ROLE_ADMIN) && $form->get('isAdmin')->getData() === true) {
            $role = new Role();
            $role->setRole(USER::ROLE_ADMIN);
            $role->setUser($user);
            $entityManager->persist($role);

            $this->addFlash('success', 'message.admin_added');
          } else {
            $this->addFlash('success', 'message.admin_added');
          }


            $user->setPassword(
                $encoder->encodePassword($user, $user->getPassword())
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function show(User $user): Response
    {
        if (!$this->isGranted(USER::ROLE_ADMIN)) {
            if (null == $this->getUser() || $user->getUsername() !== $this->getUser()->getUsername()) {
                return $this->redirectToRoute('front_page');
            }
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        if (!$this->isGranted(USER::ROLE_ADMIN)) {
            if (null == $this->getUser() || $user->getUsername() !== $this->getUser()->getUsername()) {
                return $this->redirectToRoute('front_page');
            }
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $encoder->encodePassword($user, $user->getPassword())
            );
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/addAdmin", name="user_addAdmin", methods={"GET"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     */
    public function addAdmin(Request $request, User $user): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $role = new Role();
            $role->setRole(USER::ROLE_ADMIN);
            $role->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            $this->addFlash('success', 'message.admin_added');
        }

        return $this->redirectToRoute('user_index', [
            'id' => $user->getId(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if (!$this->isGranted(USER::ROLE_ADMIN)) {
            if (null == $this->getUser() || $user->getUsername() !== $this->getUser()->getUsername()) {
                return $this->redirectToRoute('front_page');
            }
        }

        if (count($user->getDisposals()) > 0) {
          $this->addFlash('danger', 'message.cant_remove_user_having_disposals');

          return $this->redirectToRoute('user_index');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
