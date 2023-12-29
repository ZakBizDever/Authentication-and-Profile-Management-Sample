<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class SendNewsletterCommand extends Command
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private ParameterBagInterface $parameterBag)
    {
        parent::__construct();
    }

    /**
     * Configure Command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:broadcast-newsletter')
            ->setDescription('Broadcasting newsletter to active users created in the last week');
    }

    /**
     * Execute Send newsletter Command
     *
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
     * Email sending logic
     *
     * @throws TransportExceptionInterface
     */
    private function sendNewsletter($recipient): void
    {
        $email = (new Email())
            ->from($this->parameterBag->get('newsletter.sender'))
            ->to($recipient)
            ->subject($this->parameterBag->get('newsletter.subject'))
            ->text($this->parameterBag->get('newsletter.message'));

        $this->mailer->send($email);
    }
}
