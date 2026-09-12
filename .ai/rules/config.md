---
paths:
  - config/filesystems.php
  - config/livewire.php
---

# Config

## The s3 (R2) disk swallows failures — check return values
The s3 disk sets `throw => false`, so a failed `put()` returns false instead of raising; `get()` comes back as an empty string. A broken upload looks exactly like a successful one.

Never treat "no exception" as proof a write to the films disk worked — check the return value, or wrap the call so the studio surfaces a real error to the user. This masked a total TLS failure during the R2 bring-up and made a dead connection look healthy.

## Livewire direct-to-R2 uploads do work — the ACL worry was unfounded
`LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK=s3` makes Livewire sign a presigned PUT carrying `x-amz-acl: private` (see `GenerateSignedUploadUrl::forS3`). That was assumed to break R2, because R2 does not implement ACLs.

Tested against the dev bucket on 2026-09-12: R2 returned **200 and stored the object**. It tolerates the `private` ACL; it is `public-read` that R2 rejects, and Livewire never sends that.

So this is safe to turn on, and worth it in production — large films then upload straight to the bucket instead of through PHP. It additionally needs a bucket CORS policy allowing PUT from the site's origin, which the PHP-side test above did not exercise.
