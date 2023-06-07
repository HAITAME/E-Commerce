<?php
namespace App\Controller;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/cart",name="cart_")
     */
class CartController extends AbstractController{
    /**
     * @Route("/",name="index")
     */

     // get panier
    public function index(SessionInterface $session,ProductsRepository $productsRepository){
        $panier=$session->get("panier",[]);
        $dataPanier = [];
        $total=0;
        foreach($panier as $id=>$quantite){
               $product=$productsRepository->find($id);
               $dataPanier[]=[
                "produit"=>$product,
                "quantite"=>$quantite
               ];
               $total+=$product->getPrice()*$quantite;
        }
        $isEmpty = empty($dataPanier);
        return $this->render('cart/index.html.twig',compact("dataPanier","total","isEmpty"));
    }

    /**
     * @Route("/add/{id}",name="add")
     */
    // fct ajouter prod dans le panier 

    public function add(Products $product,SessionInterface $session){
        $panier=$session->get("panier",[]);
        $id=$product->getId();
        if(!empty($panier[$id])){
            $panier[$id]++;

        }else{
            $panier[$id]=1;
        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("cart_index");
    

    }

    /**
     * @Route("/remove/{id}",name="remove")
     */

     // suprimer un prod
     public function remove(Products $product,SessionInterface $session){
        $panier=$session->get("panier",[]);
        $id=$product->getId();
        if(!empty($panier[$id])){
            if($panier[$id]>1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
            

        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("cart_index");
    

    }

    /**
     * @Route("/delete/{id}",name="delete")
     */

     // suprimer tout les prod

     public function delete(Products $product,SessionInterface $session){
        $panier=$session->get("panier",[]);
        $id=$product->getId();
        if(!empty($panier[$id])){
            
                unset($panier[$id]);
            
            

        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("cart_index");
    

    }

}