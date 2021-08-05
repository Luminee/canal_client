<?php

namespace Lib\Core;

use Exception;
use Com\Alibaba\Otter\Canal\Protocol\Entry;
use Com\Alibaba\Otter\Canal\Protocol\EntryType;
use Com\Alibaba\Otter\Canal\Protocol\RowChange;

class Parser
{
    protected $transaction = [EntryType::TRANSACTIONBEGIN, EntryType::TRANSACTIONEND];

    /**
     * @param Entry $entry
     * @return null | array
     * @throws Exception
     */
    public function parseEntry($entry)
    {
        if (in_array($entry->getEntryType(), $this->transaction)) return null;
        $header = $entry->getHeader();
        $_index = $header->getSchemaName() . '.' . $header->getTableName();
        $rowChange = new RowChange();
        $rowChange->mergeFromString($entry->getStoreValue());
        return [$_index, $rowChange];
    }

}