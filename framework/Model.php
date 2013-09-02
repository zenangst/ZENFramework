<?php
if (class_exists('Configuration')) {
    class Model extends Configuration {}
} else {
    class Model extends ZENObject {}
}
