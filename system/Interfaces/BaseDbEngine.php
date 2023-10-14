<?php 

namespace Interfaces;

interface BaseDbEngine {
    function query($query);

    function connect();
}
