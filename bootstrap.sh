#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
IMG_DIR="${ROOT_DIR}/htdocs/img"
BASES_DIR="${ROOT_DIR}/bases"
BASES_WORK_DIR="${ROOT_DIR}/bases-work"
TMP_DIR="${ROOT_DIR}/tmp/bootstrap"

usage() {
  cat <<'USAGE'
Usage: ./bootstrap.sh [options]

Targets:
  --img                  Process htdocs/img
  --bases                Process bases/
  --bases-work           Process bases-work/
  --all                  Process img + bases + bases-work

Image source options (htdocs/img):
  --img-archive-path <path>
  --img-archive-url <url>
  --img-source-dir <path>

Bases source options (bases/):
  --bases-archive-path <path>
  --bases-archive-url <url>
  --bases-source-dir <path>

Bases-work source options (bases-work/):
  --bases-work-archive-path <path>
  --bases-work-archive-url <url>
  --bases-work-source-dir <path>

Legacy compatibility (same as img-* options):
  --archive-path <path>
  --archive-url <url>
  --source-dir <path>

Other options:
  --from-env             Load .env from repository root before processing
  --force                Remove existing target directory before import
  --help                 Show this help

Environment alternatives:
  IMG_ARCHIVE_PATH / IMG_ARCHIVE_URL / IMG_SOURCE_DIR
  BASES_ARCHIVE_PATH / BASES_ARCHIVE_URL / BASES_SOURCE_DIR
  BASES_WORK_ARCHIVE_PATH / BASES_WORK_ARCHIVE_URL / BASES_WORK_SOURCE_DIR

Examples:
  ./bootstrap.sh --all \
    --img-archive-url https://.../proceedings-img.tar.gz \
    --bases-archive-url https://.../proceedings-bases.tar.gz \
    --bases-work-archive-url https://.../bases-work.tar.gz \
    --force

  ./bootstrap.sh --img --img-source-dir /mnt/shared/proceedings-img --force
USAGE
}

# Sources from environment
IMG_ARCHIVE_PATH="${IMG_ARCHIVE_PATH:-}"
IMG_ARCHIVE_URL="${IMG_ARCHIVE_URL:-}"
IMG_SOURCE_DIR="${IMG_SOURCE_DIR:-}"

BASES_ARCHIVE_PATH="${BASES_ARCHIVE_PATH:-}"
BASES_ARCHIVE_URL="${BASES_ARCHIVE_URL:-}"
BASES_SOURCE_DIR="${BASES_SOURCE_DIR:-}"

BASES_WORK_ARCHIVE_PATH="${BASES_WORK_ARCHIVE_PATH:-}"
BASES_WORK_ARCHIVE_URL="${BASES_WORK_ARCHIVE_URL:-}"
BASES_WORK_SOURCE_DIR="${BASES_WORK_SOURCE_DIR:-}"

DO_IMG=0
DO_BASES=0
DO_BASES_WORK=0
FORCE=0
FROM_ENV=0

while [[ $# -gt 0 ]]; do
  case "$1" in
    --img)
      DO_IMG=1
      shift
      ;;
    --bases)
      DO_BASES=1
      shift
      ;;
    --bases-work)
      DO_BASES_WORK=1
      shift
      ;;
    --all)
      DO_IMG=1
      DO_BASES=1
      DO_BASES_WORK=1
      shift
      ;;
    --img-archive-path)
      IMG_ARCHIVE_PATH="${2:-}"
      shift 2
      ;;
    --img-archive-url)
      IMG_ARCHIVE_URL="${2:-}"
      shift 2
      ;;
    --img-source-dir)
      IMG_SOURCE_DIR="${2:-}"
      shift 2
      ;;
    --bases-archive-path)
      BASES_ARCHIVE_PATH="${2:-}"
      shift 2
      ;;
    --bases-archive-url)
      BASES_ARCHIVE_URL="${2:-}"
      shift 2
      ;;
    --bases-source-dir)
      BASES_SOURCE_DIR="${2:-}"
      shift 2
      ;;
    --bases-work-archive-path)
      BASES_WORK_ARCHIVE_PATH="${2:-}"
      shift 2
      ;;
    --bases-work-archive-url)
      BASES_WORK_ARCHIVE_URL="${2:-}"
      shift 2
      ;;
    --bases-work-source-dir)
      BASES_WORK_SOURCE_DIR="${2:-}"
      shift 2
      ;;
    # Legacy image options
    --archive-path)
      IMG_ARCHIVE_PATH="${2:-}"
      shift 2
      ;;
    --archive-url)
      IMG_ARCHIVE_URL="${2:-}"
      shift 2
      ;;
    --source-dir)
      IMG_SOURCE_DIR="${2:-}"
      shift 2
      ;;
    --from-env)
      FROM_ENV=1
      shift
      ;;
    --force)
      FORCE=1
      shift
      ;;
    --help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown option: $1" >&2
      usage
      exit 1
      ;;
  esac
done

if [[ $FROM_ENV -eq 1 ]]; then
  CLI_IMG_ARCHIVE_PATH="$IMG_ARCHIVE_PATH"
  CLI_IMG_ARCHIVE_URL="$IMG_ARCHIVE_URL"
  CLI_IMG_SOURCE_DIR="$IMG_SOURCE_DIR"
  CLI_BASES_ARCHIVE_PATH="$BASES_ARCHIVE_PATH"
  CLI_BASES_ARCHIVE_URL="$BASES_ARCHIVE_URL"
  CLI_BASES_SOURCE_DIR="$BASES_SOURCE_DIR"
  CLI_BASES_WORK_ARCHIVE_PATH="$BASES_WORK_ARCHIVE_PATH"
  CLI_BASES_WORK_ARCHIVE_URL="$BASES_WORK_ARCHIVE_URL"
  CLI_BASES_WORK_SOURCE_DIR="$BASES_WORK_SOURCE_DIR"

  ENV_FILE="$ROOT_DIR/.env"
  if [[ ! -f "$ENV_FILE" ]]; then
    echo "Error: --from-env was used but .env was not found at $ENV_FILE" >&2
    exit 1
  fi
  set -a
  # shellcheck disable=SC1090
  source "$ENV_FILE"
  set +a

  # CLI args override .env values.
  [[ -n "$CLI_IMG_ARCHIVE_PATH" ]] && IMG_ARCHIVE_PATH="$CLI_IMG_ARCHIVE_PATH"
  [[ -n "$CLI_IMG_ARCHIVE_URL" ]] && IMG_ARCHIVE_URL="$CLI_IMG_ARCHIVE_URL"
  [[ -n "$CLI_IMG_SOURCE_DIR" ]] && IMG_SOURCE_DIR="$CLI_IMG_SOURCE_DIR"
  [[ -n "$CLI_BASES_ARCHIVE_PATH" ]] && BASES_ARCHIVE_PATH="$CLI_BASES_ARCHIVE_PATH"
  [[ -n "$CLI_BASES_ARCHIVE_URL" ]] && BASES_ARCHIVE_URL="$CLI_BASES_ARCHIVE_URL"
  [[ -n "$CLI_BASES_SOURCE_DIR" ]] && BASES_SOURCE_DIR="$CLI_BASES_SOURCE_DIR"
  [[ -n "$CLI_BASES_WORK_ARCHIVE_PATH" ]] && BASES_WORK_ARCHIVE_PATH="$CLI_BASES_WORK_ARCHIVE_PATH"
  [[ -n "$CLI_BASES_WORK_ARCHIVE_URL" ]] && BASES_WORK_ARCHIVE_URL="$CLI_BASES_WORK_ARCHIVE_URL"
  [[ -n "$CLI_BASES_WORK_SOURCE_DIR" ]] && BASES_WORK_SOURCE_DIR="$CLI_BASES_WORK_SOURCE_DIR"
fi

# Infer targets from provided sources when not explicit.
if [[ $DO_IMG -eq 0 && $DO_BASES -eq 0 && $DO_BASES_WORK -eq 0 ]]; then
  [[ -n "$IMG_ARCHIVE_PATH" || -n "$IMG_ARCHIVE_URL" || -n "$IMG_SOURCE_DIR" ]] && DO_IMG=1
  [[ -n "$BASES_ARCHIVE_PATH" || -n "$BASES_ARCHIVE_URL" || -n "$BASES_SOURCE_DIR" ]] && DO_BASES=1
  [[ -n "$BASES_WORK_ARCHIVE_PATH" || -n "$BASES_WORK_ARCHIVE_URL" || -n "$BASES_WORK_SOURCE_DIR" ]] && DO_BASES_WORK=1
fi

if [[ $DO_IMG -eq 0 && $DO_BASES -eq 0 && $DO_BASES_WORK -eq 0 ]]; then
  echo "Error: no target selected and no source provided." >&2
  usage
  exit 1
fi

mkdir -p "$TMP_DIR"

extract_archive_to_stage() {
  local archive="$1"
  local stage_dir="$2"

  rm -rf "$stage_dir"
  mkdir -p "$stage_dir"

  case "$archive" in
    *.tar.gz|*.tgz)
      tar -xzf "$archive" -C "$stage_dir"
      ;;
    *.tar)
      tar -xf "$archive" -C "$stage_dir"
      ;;
    *.zip)
      unzip -q "$archive" -d "$stage_dir"
      ;;
    *)
      echo "Unsupported archive format: $archive" >&2
      exit 1
      ;;
  esac
}

copy_from_stage() {
  local kind="$1"
  local stage_dir="$2"
  local target_dir="$3"

  case "$kind" in
    img)
      if [[ -d "$stage_dir/img" ]]; then
        cp -a "$stage_dir/img/." "$target_dir/"
      elif [[ -d "$stage_dir/htdocs/img" ]]; then
        cp -a "$stage_dir/htdocs/img/." "$target_dir/"
      else
        cp -a "$stage_dir/." "$target_dir/"
      fi
      ;;
    bases)
      if [[ -d "$stage_dir/bases" ]]; then
        cp -a "$stage_dir/bases/." "$target_dir/"
      else
        cp -a "$stage_dir/." "$target_dir/"
      fi
      ;;
    bases-work)
      if [[ -d "$stage_dir/bases-work" ]]; then
        cp -a "$stage_dir/bases-work/." "$target_dir/"
      else
        cp -a "$stage_dir/." "$target_dir/"
      fi
      ;;
    *)
      echo "Unknown kind: $kind" >&2
      exit 1
      ;;
  esac
}

populate_target() {
  local kind="$1"
  local target_dir="$2"
  local archive_path="$3"
  local archive_url="$4"
  local source_dir="$5"

  local stage_dir="$TMP_DIR/stage-$kind"
  local download_path=""

  if [[ $FORCE -eq 1 && -e "$target_dir" ]]; then
    echo "Removing existing $target_dir ..."
    rm -rf "$target_dir"
  fi
  mkdir -p "$target_dir"

  if [[ -n "$source_dir" ]]; then
    if [[ ! -d "$source_dir" ]]; then
      echo "Source directory not found for $kind: $source_dir" >&2
      exit 1
    fi
    echo "[$kind] Copying from source directory: $source_dir"
    cp -a "$source_dir/." "$target_dir/"
  elif [[ -n "$archive_path" ]]; then
    if [[ ! -f "$archive_path" ]]; then
      echo "Archive not found for $kind: $archive_path" >&2
      exit 1
    fi
    echo "[$kind] Extracting archive: $archive_path"
    extract_archive_to_stage "$archive_path" "$stage_dir"
    copy_from_stage "$kind" "$stage_dir" "$target_dir"
  elif [[ -n "$archive_url" ]]; then
    local archive_name
    archive_name="$(basename "${archive_url%%\?*}")"
    [[ -z "$archive_name" ]] && archive_name="$kind.tar.gz"
    download_path="$TMP_DIR/$archive_name"
    echo "[$kind] Downloading archive: $archive_url"
    curl -fL "$archive_url" -o "$download_path"
    echo "[$kind] Extracting downloaded archive: $download_path"
    extract_archive_to_stage "$download_path" "$stage_dir"
    copy_from_stage "$kind" "$stage_dir" "$target_dir"
  else
    echo "Error: no source defined for target '$kind'." >&2
    exit 1
  fi

  local file_count dir_size
  file_count="$(find "$target_dir" -type f 2>/dev/null | wc -l | tr -d ' ')"
  dir_size="$(du -sh "$target_dir" | awk '{print $1}')"

  if [[ "$file_count" -eq 0 ]]; then
    echo "[$kind] Finished, but target is empty: $target_dir" >&2
    exit 1
  fi

  echo "[$kind] Ready: $target_dir"
  echo "[$kind] Files: $file_count"
  echo "[$kind] Size: $dir_size"
}

if [[ $DO_IMG -eq 1 ]]; then
  populate_target "img" "$IMG_DIR" "$IMG_ARCHIVE_PATH" "$IMG_ARCHIVE_URL" "$IMG_SOURCE_DIR"
fi
if [[ $DO_BASES -eq 1 ]]; then
  populate_target "bases" "$BASES_DIR" "$BASES_ARCHIVE_PATH" "$BASES_ARCHIVE_URL" "$BASES_SOURCE_DIR"
fi
if [[ $DO_BASES_WORK -eq 1 ]]; then
  populate_target "bases-work" "$BASES_WORK_DIR" "$BASES_WORK_ARCHIVE_PATH" "$BASES_WORK_ARCHIVE_URL" "$BASES_WORK_SOURCE_DIR"
fi

echo "Bootstrap finished successfully."
