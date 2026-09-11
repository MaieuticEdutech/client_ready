---
paths:
  - config/filesystems.php
  - config/livewire.php
---

# Config

## The s3 (R2) disk swallows failures — check return values
The s3 disk sets `throw => false`, so a failed `put()` returns false instead of raising; `get()` comes back as an empty string. A broken upload looks exactly like a successful one.

Never treat "no exception" as proof a write to the films disk worked — check the return value, or wrap the call so the studio surfaces a real error to the user. This masked a total TLS failure during the R2 bring-up and made a dead connection look healthy.

## Keep Livewire temporary uploads off the s3 disk
Leave `livewire.temporary_file_upload.disk` unset so temp uploads land on the local disk and only the final `store()` does a server-side PutObject to R2.

Setting `LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK=s3` makes Livewire sign presigned upload URLs that carry an ACL header. R2 does not implement ACLs and rejects them. Tempting to reach for given the 2GB upload cap, but it will break uploads — this path has never been exercised against the live bucket.
