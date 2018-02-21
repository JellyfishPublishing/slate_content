Summary: Concrete5 Package - Slate Content
Name: slate_content
Version: %{version}
Release: 1
License: Not Applicable
Group: Development/Library
BuildRoot: %{_tmppath}/%{name}-root
Source0: %{name}-%{version}.tar.gz
BuildArch: noarch

%description
Concrete5 content for Slate Campaign sites

%prep
%setup -q

%install
[ "$RPM_BUILD_ROOT" != "/" ] && rm -rf $RPM_BUILD_ROOT

install -d -m 755 $RPM_BUILD_ROOT/usr/share/concrete5/packages/slate_content
cp -R * $RPM_BUILD_ROOT/usr/share/concrete5/packages/slate_content

%clean
[ "$RPM_BUILD_ROOT" != "/" ] && rm -rf $RPM_BUILD_ROOT

%files
%defattr(-,root,root,-)
/usr/share/concrete5/packages/slate_content
