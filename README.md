# A PdoStatement for Developers

[![Build Status](https://travis-ci.org/kumamidori/Kumamidori.DevPdoStatement.svg?branch=master)](https://travis-ci.org/kumamidori/Kumamidori.DevPdoStatement)

[kumamidori.DevPdoStatement](https://github.com/kumamidori/DevPdoStatement) は、[Aura.SqlQuery](http://bearsunday.github.io/manuals/1.0/ja/database.html) のSQLを記録します。
[koriym/dev-pdo-statement](https://packagist.org/packages/koriym/dev-pdo-statement)  を改変して、パッケージ名を変更して作りました。

## koriym/dev-pdo-statement からの改変内容

- １つのクエリーの中でパラメータを複数回指定していた場合でもクエリー出力ができるように改善
- Aura.SqlQuery でのクエリー出力に対応
- ベンダー名を変更して別パッケージ化
