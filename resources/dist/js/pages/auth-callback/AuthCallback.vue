<template>
    <div>
        <script type="application/javascript">
            (function () {
                window.opener.postMessage(window.location.search, window.opener.location.origin);
                window.close();
            })();
        </script>
    </div>
</template>

<script>
    export default {

    }
</script>
