<?php

// src/Controller/ChatController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ChatType;

class ChatController extends AbstractController
{
    #[Route('/chats', name: 'chat_list')]
    public function chatList(EntityManagerInterface $entityManager, UserInterface $user)
    {
        // Fetch chats where the authenticated user is either the sender or the receiver
        $chats = $entityManager->getRepository(Message::class)->findUserChats($user->getId());
        // dd($chats);
        return $this->render('chat/chat_list.html.twig', [
            'chats' => $chats,
            'user' => $user,
        ]);
    }

    #[Route('/chat/{receiverId}', name: 'chat_show')]
    public function chatShow(Request $request, $receiverId, EntityManagerInterface $entityManager, UserInterface $user)
    {
        $otherUser = $entityManager->getRepository(User::class)->findOneById($receiverId);
        $messages = $entityManager->getRepository(Message::class)->findUserMessages($user->getId(), $receiverId);

        $message = new Message();
        $messageForm = $this->createForm(ChatType::class, $message);
        $messageForm->handleRequest($request);

        if( $messageForm->isSubmitted() && $messageForm->isValid() ) {
            $message->setSender($user);
            $message->setReceiver($otherUser);
            $message->setSentAt(new \DateTimeImmutable());

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully sent message');
            return $this->redirectToRoute('chat_show', ['receiverId' => $receiverId]);
        }
        
        return $this->render('chat/chat_show.html.twig', [
            'messageForm' => $messageForm->createView(),
            'messages' => $messages,
            'receiverId' => $receiverId
        ]);
    }

    #[Route('/offer/{productId}/send', name: 'app_send_offer')]
    public function sendOffer(Request $request, EntityManagerInterface $entityManager, int $productId): Response
    {
        
        $offer = new Message();

        $offer->setSender($user);
        $offer->setReceiver($otherUser);
        $offer->setSentAt(new \DateTimeImmutable());

        // Persist the changes
        $entityManager->persist($offer);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}