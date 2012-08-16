<?php
/*
 * Whitewashing
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace Whitewashing\Bundle\LogglyBundle\Monolog\Handler;

use Whitewashing\Bundle\LogglyBundle\WhitewashingLogglyBundle;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\JsonFormatter;

/**
 * A loggly log handler for Symfony. It uses the Loggly HTTP API to push
 * logfiles to the cloud service loggly.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class LogglyHandler extends AbstractProcessingHandler
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $host;

    public function __construct($key, $port = 443, $host = 'logs.loggly.com', $level = Logger::DEBUG, $bubble = true)
    {
        $this->key = $key;
        $this->port = $port;
        $this->host = $host;

        parent::__construct($level, $bubble);
    }
		
	/**
	 * {@inheritdoc}
	 */
    public function handleBatch(array $records)
    {
        $messages = array();

        foreach ($records as $record) {
            if ($record['level'] < $this->level) {
                continue;
            }
            $messages[] = $this->processRecord($record);
        }

        if (!empty($messages)) {
			$this->send((string)$this->getFormatter()->formatBatch($messages), $messages);
        }
    }

	/**
	 * {@inheritdoc}
	 */
    protected function write(array $record)
    {
        $this->send((string) $record['formatted'], array($record));
    }
    /**
     * {@inheritDoc}
     */
    protected function send($message, array $records)
    {
        $fp = fsockopen($this->getTransport(), $this->port, $errno, $errstr, 30);
        if (!$fp) {
            return false;
        }

        $request = "POST /inputs/".$this->key." HTTP/1.1\r\n";
        $request.= "Host: ".$this->host."\r\n";
        $request.= "User-Agent: Whitewashing LogglyBundle " . WhitewashingLogglyBundle::VERSION . "\r\n";
        $request.= "Content-Type: application/json\r\n";
        $request.= "Content-Length: ".strlen($message)."\r\n";
        $request.= "Connection: Close\r\n\r\n";
        $request.= $message;

        fwrite($fp, $request);
        fclose($fp);

        return true;
    }

    /**
     * Get socket transport url
     *
     * @return string
     */
    private function getTransport()
    {
        switch ($this->port) {
            case '443':
                return 'ssl://'.$this->host;
                break;
            case '80':
                return $this->host;
                break;
            default:
                throw new \LogicException('Allowed invalid port to be set.');
                break;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter;
    }
}
