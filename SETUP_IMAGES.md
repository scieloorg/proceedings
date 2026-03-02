# Bootstrap Large Assets Outside Git

This repository excludes large runtime assets from git:

- `htdocs/img/`
- `bases/`
- `bases-work/`

Use `bootstrap.sh` to download/extract them.

## Quick Start (your Wasabi URLs)

```bash
cd /Users/rondinelisaad/Downloads/repo-scielo/proceedings_scielo_br
chmod +x bootstrap.sh

./bootstrap.sh --all \
  --img-archive-url "https://s3.us-east-1.wasabisys.com/minio/proceedings-scielo-br/proceedings-img.tar.gz" \
  --bases-archive-url "https://s3.us-east-1.wasabisys.com/minio/proceedings-scielo-br/proceedings-bases.tar.gz" \
  --bases-work-archive-url "https://s3.us-east-1.wasabisys.com/minio/proceedings-scielo-br/bases-work.tar.gz" \
  --force
```

## What the script does

- Downloads archive when `--*-archive-url` is used.
- Extracts `.tar.gz`, `.tgz`, `.tar`, or `.zip`.
- Detects common roots automatically:
  - `img/` or `htdocs/img/` for image package
  - `bases/` for bases package
  - `bases-work/` for bases-work package
- Copies to the project paths.
- Prints file count and size for each target.

## Optional `.env` workflow

```bash
cp .env.example .env
# edit .env
./bootstrap.sh --from-env --all --force
```

## Start services after bootstrap

```bash
docker compose up -d
```

## Git LFS alternative (optional)

If you prefer to keep binaries in git:

```bash
git lfs install
git lfs track "htdocs/img/**"
git add .gitattributes
```

Note: LFS has storage/bandwidth quotas.
