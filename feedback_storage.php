<?php

class FeedbackStorage
{
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function saveMessage($name, $email, $age, $comment)
    {
        $row = array(
            'date' => date('Y-m-d H:i:s'),
            'name' => $name,
            'email' => $email,
            'age' => $age,
            'comment' => $comment
        );

        $line = json_encode($row, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        file_put_contents($this->filePath, $line, FILE_APPEND);
    }

    public function getMessages()
    {
        $result = array();

        if (!file_exists($this->filePath)) {
            return $result;
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $item = json_decode($line, true);

            if (is_array($item)) {
                $result[] = $item;
            }
        }

        return $result;
    }

    public function deleteMessage($index)
    {
        if (!file_exists($this->filePath)) {
            return;
        }

        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (isset($lines[$index])) {
            unset($lines[$index]);
            file_put_contents($this->filePath, implode(PHP_EOL, $lines) . PHP_EOL);
        }
    }
}