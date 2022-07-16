<?php

namespace App\Helpers;

class Response
{
    protected $message;
    protected $extra;

    /**
     * @param int $status
     * @param string $message
     * @param array $extra
     */
    public function __construct(int $status = 200, string $message = '', array $extra = []) {
        //TODO Add validation
        $this->setData($status, $message, $extra);
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status): self {
        http_response_code($status);

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function setMessage($message): self {
        $this->message = $message;

        return $this;
    }

    /**
     * @param array $extra
     * @return $this
     */
    public function setExtra(array $extra): self {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @param int $status
     * @param string $message
     * @param array $extra
     * @return void
     */
    protected function setData(int $status, string $message, array $extra = []) {
        http_response_code($status);
        $this->message = $message;
        $this->extra = $extra;
    }

    /**
     * @return array
     */
    public function message(): array {
        return [
            'message' => $this->message,
            'extra' => $this->extra,
        ];
    }
}
