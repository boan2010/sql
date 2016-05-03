<?php

namespace mindplay\sql\framework;

use mindplay\sql\model\Column;

interface SequencedExecutable
{
    /**
     * @return Column[] list of auto-sequenced Columns
     */
    public function getSequencedColumns();
}
