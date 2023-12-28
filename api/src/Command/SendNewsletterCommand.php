<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class SendNewsletterCommand extends Command
{
    private $mailer;
    private $entityManager;

    private $userRepository;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        parent::__construct();

        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this->setName('app:broadcast-newsletter')
            ->setDescription('Broadcasting newsletter to active users created in the last week');
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $weekAgo = new \DateTime('-7 days');

        $users = $this->userRepository->findActiveUsersCreatedInLastWeek();

        foreach ($users as $user) {
            $this->sendNewsletter($user->getEmail());
        }

        $output->writeln(sprintf('Newsletter sent to %s active users successfully.', \count($users)));

        return 0;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendNewsletter($recipient): void
    {
        $email = (new Email())
            ->from('newsletter@cobbleweb.com')
            ->to($recipient)
            ->subject('Your best newsletter')
            ->text('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id interdum nibh. Phasellus blandit tortor in cursus convallis. Praesent et tellus fermentum, pellentesque lectus at, tincidunt risus. Quisque in nisl malesuada, aliquet nibh at, molestie libero.');

        $this->mailer->send($email);
    }
}
