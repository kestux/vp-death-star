<?php

namespace App\Command;

use App\Generator\DroidPathGenerator;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @coversDefaultClass \App\Command\DroidControlCommand
 * @group functional-tests
 */
class DroidControlCommandTest extends TestCase
{
    private ApplicationTester $appTester;

    /** @var ClientInterface&MockObject $clientMock*/
    private ClientInterface $clientMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(ClientInterface::class);

        $app = new Application();
        $app->add(new DroidControlCommand($this->clientMock, new DroidPathGenerator(), 0, 'droid control'));
        $app->setAutoExit(false);

        $this->appTester = new ApplicationTester($app);
    }

    /**
     * @covers ::execute
     *
     * Path:
     * #    o    #
     * ######   ##
     * #         #
     * ###   #####
     */
    public function testDroidsCoverPath(): void
    {
        $this->clientMock->expects(self::exactly(14))
            ->method('request')
            ->withConsecutive(
                ['GET', '?name=Kestutis+Kacinskas&path=f'],
                ['GET', '?name=Kestutis+Kacinskas&path=r'],
                ['GET', '?name=Kestutis+Kacinskas&path=rf'],
                ['GET', '?name=Kestutis+Kacinskas&path=rff'],
                ['GET', '?name=Kestutis+Kacinskas&path=rfff'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffr'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffrf'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffrr'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffrrf'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffrrr'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffrrrf'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffrrrr'],
                ['GET', '?name=Kestutis+Kacinskas&path=rffl'],
                ['GET', '?name=Kestutis+Kacinskas&path=rfflf']
            )
            ->willReturnOnConsecutiveCalls(
                new Response(417), // f,
                new Response(410), // r,
                new Response(410), // rf,
                new Response(410), // rff,
                new Response(417), // rfff,
                new Response(410), // rffr
                new Response(417), // rffrf
                new Response(410), // rffrr
                new Response(417), // rffrrf
                new Response(410), // rffrrr
                new Response(417), // rffrrrf
                new Response(417), // rffrrrr
                new Response(410), // rffl
                new Response(200)  // rfflf
            );

        $this->appTester->run([
            'command' => 'droid control',
            '--stock' => 20
        ]);

        echo $this->appTester->getDisplay();
    }
}
