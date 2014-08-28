<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\DemoBundle\Entity\Book;
use Acme\DemoBundle\Form\Type\BookType;
use Acme\DemoBundle\Form\Type\BookFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Book controller.
 *
 * @Route("/books")
 */
class BookController extends Controller
{
    /**
     * Lists all Book entities.
     *
     * @Route("/", name="books")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new BookFilterType());
        if (!is_null($response = $this->saveFilter($form, 'book', 'books'))) {
            return $response;
        }

        $qb = $em->getRepository('AcmeDemoBundle:Book')->createQueryBuilder('b');
        if(isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0)
        {
           $page =$_GET['page'] - 1;
        }
	else
        {
           $page = 0;
        }
           $qb->setFirstResult(($page)*5);
	   $qb->setMaxResults(6);
           $paginator = $qb->getQuery()->getResult();
           $count = count($paginator);
           $paginator = array_slice($paginator, 0, 5);
        if($count < 6)
           $prev_page = 0;
        else
           $prev_page = $page + 2;
        return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
            'prev_page' => $page,
            'next_page' => $prev_page,
        );
    }

    /**
     * Finds and displays a Book entity.
     *
     * @Route("/{id}/show", name="books_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Book $book)
    {
        $deleteForm = $this->createDeleteForm($book->getId());

        return array(
            'book' => $book,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Book entity.
     *
     * @Route("/new", name="books_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $book = new Book();
        $form   = $this->createForm(new BookType(), $book);

        return array(
            'book' => $book,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Book entity.
     *
     * @Route("/create", name="books_create")
     * @Method("POST")
     * @Template("AcmeDemoBundle:Book:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(new BookType(), $book);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirect($this->generateUrl('books_show', array('id' => $book->getId())));
        }

        return array(
            'book' => $book,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Book entity.
     *
     * @Route("/{id}/edit", name="books_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Book $book)
    {
        $editForm = $this->createForm(new BookType(), $book, array(
            'action' => $this->generateUrl('books_update', array('id' => $book->getid())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($book->getId());

        return array(
            'book' => $book,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Book entity.
     *
     * @Route("/{id}/update", name="books_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("AcmeDemoBundle:Book:edit.html.twig")
     */
    public function updateAction(Book $book, Request $request)
    {
        $editForm = $this->createForm(new BookType(), $book, array(
            'action' => $this->generateUrl('books_update', array('id' => $book->getid())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('books_edit', array('id' => $book->getId())));
        }
        $deleteForm = $this->createDeleteForm($book->getId());

        return array(
            'book' => $book,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="books_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('book', $field, $type);

        return $this->redirect($this->generateUrl('books'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Save filters
     *
     * @param  FormInterface $form
     * @param  string        $name   route/entity name
     * @param  string        $route  route name, if different from entity name
     * @param  array         $params possible route parameters
     * @return Response
     */
    protected function saveFilter(FormInterface $form, $name, $route = null, array $params = null)
    {
        $request = $this->getRequest();
        $url = $this->generateUrl($route ?: $name, is_null($params) ? array() : $params);
        if ($request->query->has('submit-filter') && $form->handleRequest($request)->isValid()) {
            $request->getSession()->set('filter.' . $name, $request->query->get($form->getName()));

            return $this->redirect($url);
        } elseif ($request->query->has('reset-filter')) {
            $request->getSession()->set('filter.' . $name, null);

            return $this->redirect($url);
        }
    }

    /**
     * Filter form
     *
     * @param  FormInterface                                       $form
     * @param  QueryBuilder                                        $qb
     * @param  string                                              $name
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    protected function filter(FormInterface $form, QueryBuilder $qb, $name)
    {
        if (!is_null($values = $this->getFilter($name))) {
            if ($form->submit($values)->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $qb);
            }
        }

        // possible sorting
        $this->addQueryBuilderSort($qb, $name);
        return $this->get('knp_paginator')->paginate($qb->getQuery(), $this->getRequest()->query->get('page', 1), 20);
    }

    /**
     * Get filters from session
     *
     * @param  string $name
     * @return array
     */
    protected function getFilter($name)
    {
        return $this->getRequest()->getSession()->get('filter.' . $name);
    }

    /**
     * Deletes a Book entity.
     *
     * @Route("/{id}/delete", name="books_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Book $book, Request $request)
    {
        $form = $this->createDeleteForm($book->getId());
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('books'));
    }

    /**
     * Create Delete form
     *
     * @param integer $id
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl('books_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
