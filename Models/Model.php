<?php

namespace App\Models;

class Model implements ModelInterface
{
    protected $dbTable;
    /**
     * @return string
     */
    public function getTableName(): string {
        return $this->dbTable;
    }

    /**
     * @return void
     */
    public function resetProps(): void {
        foreach($this as $key => $value) {
            unset($this->key);
        }
    }
}
