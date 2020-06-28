# 1.0.0

## Changed

* Renamed namespace `Core23\SetlistFMBundle` to `Nucleos\SetlistFMBundle` after move to [@nucleos]

  Run

  ```
  $ composer remove core23/setlistfm-bundle
  ```

  and

  ```
  $ composer require nucleos/setlistfm-bundle
  ```

  to update.

  Run

  ```
  $ find . -type f -exec sed -i '.bak' 's/Core23\\SetlistFMBundle/Nucleos\\SetlistFMBundle/g' {} \;
  ```

  to replace occurrences of `Core23\SetlistFMBundle` with `Nucleos\SetlistFMBundle`.

  Run

  ```
  $ find -type f -name '*.bak' -delete
  ```

  to delete backup files created in the previous step.
